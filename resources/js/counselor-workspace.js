export const promptLength = (value) => Array.from(value).length;
export const canSend = (value, sending) => Boolean(value.trim()) && promptLength(value) <= 1000 && !sending;
export const requestError = (status, payload = {}) => {
    if (status === 401 || status === 419) return 'Your session has expired. Sign in again before sending.';
    if (status === 429) return 'You have sent several requests. Wait a minute and try again.';
    return Object.values(payload.errors || {}).flat()[0] || payload.message || 'The request could not be completed. Please try again.';
};

export default function counselorWorkspace(config) {
    return {
        conversations: config.conversations || [], messages: config.active?.messages || [],
        activeId: config.active?.id || null, title: config.active?.title || 'A new perspective starts here',
        olderUrl: config.active?.older_url || null, viewingOlder: config.active?.viewing_older || false,
        historyQuery: config.historySearch || '', draft: '', sending: false, error: '', failed: null,
        sidebarCollapsed: false, historyOpen: false, contextOpen: true, contextDrawerOpen: false,
        selectedContextId: null, atBottom: true, copiedId: null,
        editOpen: false, deleteOpen: false, editTitle: '', managementError: '', managing: false,
        authenticated: config.authenticated, viewportWidth: window.innerWidth,
        init() {
            this.$watch('messages.length', () => this.scrollToLatest());
            this.$nextTick(() => { if (!this.viewingOlder) this.scrollToLatest(true); });
        },
        get characterCount() { return promptLength(this.draft); },
        get readyToSend() { return canSend(this.draft, this.sending); },
        get filteredConversations() { return this.conversations.filter((item) => item.title.toLowerCase().includes(this.historyQuery.toLowerCase())); },
        groupConversations(group) { return this.filteredConversations.filter((item) => item.group === group); },
        get activeSummary() { return this.conversations.find((item) => item.id === this.activeId); },
        get contextMessage() {
            return this.selectedContextId ? this.messages.find((message) => message.id === this.selectedContextId)
                : [...this.messages].reverse().find((message) => message.role !== 'user');
        },
        get contextCards() { return this.contextMessage?.inline_cards || []; },
        get contextSources() { return this.contextMessage?.citations || []; },
        get hasContext() { return this.contextCards.length > 0 || this.contextSources.length > 0; },
        humanize(value) { return (value || 'Unknown').replaceAll('_', ' '); },
        formatDate(value) {
            const date = new Date(value);
            return value && !Number.isNaN(date.valueOf()) ? new Intl.DateTimeFormat(undefined, { dateStyle: 'medium' }).format(date) : 'Date unavailable';
        },
        formatTime(value) {
            const date = new Date(value);
            return value && !Number.isNaN(date.valueOf()) ? new Intl.DateTimeFormat(undefined, { hour: 'numeric', minute: '2-digit' }).format(date) : '';
        },
        resizeComposer(element) {
            element.style.height = 'auto';
            element.style.height = `${Math.min(element.scrollHeight, Math.max(64, Math.min(180, (window.visualViewport?.height || window.innerHeight) * 0.28)))}px`;
        },
        usePrompt(prompt) {
            this.draft = prompt;
            this.$nextTick(() => { this.resizeComposer(this.$refs.composer); this.$refs.composer.focus(); });
        },
        scrollToLatest(force = false) {
            if (!force && !this.atBottom) return;
            this.$nextTick(() => {
                const feed = this.$refs.feed;
                feed.scrollTop = feed.scrollHeight;
                this.atBottom = true;
            });
        },
        trackScroll() {
            const feed = this.$refs.feed;
            this.atBottom = feed.scrollHeight - feed.scrollTop - feed.clientHeight < 100;
        },
        async api(url, method, body) {
            const controller = new AbortController();
            const timeout = setTimeout(() => controller.abort(), 45000);
            try {
                const response = await fetch(url, { method, credentials: 'same-origin', signal: controller.signal,
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': config.csrf },
                    ...(body ? { body: JSON.stringify(body) } : {}) });
                const payload = await response.json().catch(() => ({}));
                if (!response.ok) throw new Error(requestError(response.status, payload));
                return payload;
            } finally { clearTimeout(timeout); }
        },
        async sendMessage(retry = null) {
            if (!this.authenticated || this.sending) return;
            const text = retry?.text || this.draft.trim();
            if (!canSend(text, false)) { this.error = 'Enter a message of 1–1,000 characters.'; return; }
            const requestId = retry?.requestId || crypto.randomUUID();
            this.messages = this.messages.filter((item) => item.requestId !== requestId);
            this.messages.push({ id: `pending-${requestId}`, requestId, role: 'user', content: text, timestamp: new Date().toISOString(), status: 'sending' });
            this.draft = ''; this.error = ''; this.sending = true; this.failed = null; this.selectedContextId = null;
            this.scrollToLatest(true);
            this.$nextTick(() => this.resizeComposer(this.$refs.composer));
            try {
                const payload = await this.api(config.messageUrl, 'POST', { message: text, conversation_id: this.activeId || 'conv-new', request_id: requestId });
                if (!payload.success || !payload.conversation_id || !Array.isArray(payload.conversation?.messages)) throw new Error('The server returned an incomplete response. Please retry this message.');
                this.activeId = payload.conversation_id; this.title = payload.conversation_title;
                this.messages = payload.conversation.messages; this.olderUrl = payload.conversation.older_url; this.viewingOlder = false;
                const { messages, older_url, viewing_older, ...summary } = payload.conversation;
                this.conversations = [summary, ...this.conversations.filter((item) => item.id !== this.activeId)].slice(0, 50);
                window.history.replaceState(null, '', payload.conversation.url);
                document.title = `${this.title} — UniGuide Counselor`;
                this.scrollToLatest();
            } catch (error) {
                this.error = error.name === 'AbortError' ? 'The connection timed out. Retry safely with the same message.' : error.message;
                this.failed = { text, requestId };
                const pending = this.messages.find((item) => item.requestId === requestId);
                if (pending) pending.status = 'failed';
            } finally { this.sending = false; }
        },
        toggleContext() {
            if (window.innerWidth >= 1440) this.contextOpen = !this.contextOpen;
            else this.contextDrawerOpen = true;
        },
        showContext(id) {
            this.selectedContextId = id;
            this.contextOpen = true;
            if (window.innerWidth < 1440) this.contextDrawerOpen = true;
        },
        async copyMessage(message) {
            try {
                await navigator.clipboard.writeText(message.content);
                this.copiedId = message.id;
                setTimeout(() => { this.copiedId = null; }, 2000);
            } catch { this.error = 'Copy is unavailable. Select the message text to copy it.'; }
        },
        openRename() { this.editTitle = this.title; this.managementError = ''; this.editOpen = true; },
        async saveTitle() {
            if (!this.editTitle.trim() || this.managing) return;
            this.managing = true;
            try {
                const data = await this.api(this.activeSummary.manage_url, 'PATCH', { title: this.editTitle.trim() });
                this.title = data.conversation.title;
                this.conversations = this.conversations.map((item) => item.id === this.activeId ? data.conversation : item);
                this.editOpen = false;
            } catch (error) { this.managementError = error.message; }
            finally { this.managing = false; }
        },
        async togglePin() {
            if (!this.activeSummary || this.managing) return;
            this.managing = true;
            try {
                const data = await this.api(this.activeSummary.manage_url, 'PATCH', { pinned: !this.activeSummary.pinned });
                this.conversations = this.conversations.map((item) => item.id === this.activeId ? data.conversation : item);
            } catch (error) { this.error = error.message; }
            finally { this.managing = false; }
        },
        async deleteConversation() {
            if (!this.activeSummary || this.managing) return;
            this.managing = true;
            try { await this.api(this.activeSummary.delete_url, 'DELETE'); window.location.assign(config.newUrl); }
            catch (error) { this.managementError = error.message; this.managing = false; }
        },
    };
}
