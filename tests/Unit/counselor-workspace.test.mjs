import { test } from 'node:test';
import assert from 'node:assert/strict';
import counselorWorkspace, { promptLength, canSend, requestError } from '../../resources/js/counselor-workspace.js';

function workspace() {
    globalThis.window = { innerWidth: 1440, innerHeight: 900, history: { replaceState() {} } };
    globalThis.document = { title: '' };
    const result = counselorWorkspace({ authenticated: true, conversations: [], active: null, messageUrl: '/message', newUrl: '/chat', csrf: 'test-csrf' });
    result.$refs = { composer: { style: {}, scrollHeight: 64 }, feed: { scrollTop: 0, scrollHeight: 400, clientHeight: 400 } };
    result.$nextTick = (callback) => callback();
    return result;
}
const reply = () => ({ success: true, conversation_id: 'conv-1', conversation_title: 'Research', conversation: { id: 'conv-1', title: 'Research', url: '/chat/conv-1', messages: [{ id: 1, role: 'user', content: 'Hello' }, { id: 2, role: 'assistant', content: 'Response', citations: [] }] } });

test('composer validates Unicode characters, whitespace and submission state', () => {
    assert.equal(promptLength('🇵🇰'), 2);
    assert.equal(canSend('😀'.repeat(1000), false), true);
    assert.equal(canSend('😀'.repeat(1001), false), false);
    assert.equal(canSend('   ', false), false);
    assert.equal(canSend('Hello', true), false);
});
test('session, rate-limit and field errors provide actionable messages', () => {
    assert.match(requestError(419), /Sign in/);
    assert.match(requestError(429), /Wait a minute/);
    assert.equal(requestError(422, { errors: { message: ['Message is too long.'] } }), 'Message is too long.');
});
test('an in-flight send cannot be submitted twice', async () => {
    const state = workspace();
    state.draft = 'Hello';
    let finish;
    let calls = 0;
    state.api = () => { calls++; return new Promise((resolve) => { finish = resolve; }); };
    const pending = state.sendMessage();
    await state.sendMessage();
    assert.equal(calls, 1);
    assert.equal(state.sending, true);
    finish(reply());
    await pending;
    assert.equal(state.sending, false);
    assert.equal(state.messages.length, 2);
    assert.equal(state.activeId, 'conv-1');
    assert.equal(state.conversations[0].messages, undefined);
});
test('connection failure keeps a retryable request identifier and does not invent an answer', async () => {
    const state = workspace();
    state.draft = 'Hello';
    let firstKey;
    state.api = async (url, method, body) => { firstKey = body.request_id; throw new Error('Offline'); };
    await state.sendMessage();
    assert.equal(state.messages.length, 1);
    assert.equal(state.messages[0].role, 'user');
    assert.equal(state.messages[0].status, 'failed');
    assert.equal(state.failed.requestId, firstKey);
    state.api = async (url, method, body) => { assert.equal(body.request_id, firstKey); return reply(); };
    await state.sendMessage(state.failed);
    assert.equal(state.messages.length, 2);
    assert.equal(state.failed, null);
});
test('empty context does not create an intelligence panel', () => {
    const state = workspace();
    assert.equal(state.hasContext, false);
    state.messages.push({ id: 1, role: 'assistant', citations: [{ url: 'https://www.hec.gov.pk' }] });
    assert.equal(state.hasContext, true);
});
test('an incomplete successful response is a recoverable error', async () => {
    const state = workspace(); state.draft = 'Hello';
    state.api = async () => ({ success: true });
    await state.sendMessage();
    assert.match(state.error, /incomplete response/);
    assert.equal(state.sending, false);
    assert.ok(state.failed);
});
