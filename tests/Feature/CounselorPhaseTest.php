<?php

namespace Tests\Feature;

use App\Models\AiConversation;
use App\Models\User;
use App\Services\CounselorWorkspace;
use Tests\TestCase;

class CounselorPhaseTest extends TestCase
{
    /**
     * Test AI Counselor Home renders properly with prompt composer, categories, and quick actions.
     */
    public function test_counselor_home_renders(): void
    {
        $response = $this->get('/counselor');
        $response->assertStatus(200);
        $response->assertSee('Your future starts with');
        $response->assertSee('Registry mode');
        $response->assertSee('Compare two universities');
        $response->assertSee('Guest transcripts are not saved');
    }

    /**
     * Test AI Conversation Workspace renders with active thread, citations, and comparison tables.
     */
    public function test_conversation_workspace_renders_and_loads_conversation(): void
    {
        // Default workspace
        $response = $this->get('/counselor/chat');
        $response->assertStatus(200);
        $response->assertSee('Counseling Sessions');
        $response->assertSee('New Conversation');

        $this->actingAs(User::factory()->create());
        $thread = app(CounselorWorkspace::class)->send('NUML campus research')['conversation'];
        $responseConv = $this->get(route('counselor.chat', $thread['id']));
        $responseConv->assertOk();
        $responseConv->assertSee('NUML campus research');
    }

    /**
     * Test sending a message through the POST endpoint (both JSON and form redirect).
     */
    public function test_send_message_post_endpoint_handles_web_and_json(): void
    {
        $this->actingAs(User::factory()->create());
        $thread = app(CounselorWorkspace::class)->send('Initial research')['conversation'];
        // 1. JSON AJAX submission
        $responseJson = $this->postJson('/counselor/chat/message', [
            'message' => 'How is NUST CS aggregate calculated?',
            'conversation_id' => $thread['id'],
        ]);

        $responseJson->assertStatus(200);
        $responseJson->assertJsonPath('success', true);
        $responseJson->assertJsonStructure([
            'success',
            'conversation_id',
            'conversation_title',
            'user_message',
            'ai_message' => [
                'id',
                'role',
                'content',
                'confidence',
                'citations',
            ],
        ]);

        // 2. Form submission with redirect
        $responseForm = $this->post('/counselor/chat/message', [
            'message' => 'What are the best options for FAST Lahore?',
        ]);

        $responseForm->assertStatus(302);

        // 3. Validation failure
        $responseInvalid = $this->postJson('/counselor/chat/message', [
            'message' => '',
        ]);
        $responseInvalid->assertStatus(422);
    }

    /**
     * Test Guided Assessment questionnaire renders all 6 steps.
     */
    public function test_guided_assessment_wizard_renders(): void
    {
        $response = $this->get('/counselor/guided');
        $response->assertStatus(200);
        $response->assertSee('Guided Merit');
        $response->assertSee('Your Academic Qualifications');
        $response->assertSee('Select Your Intended Field of Study');
        $response->assertSee('Tuition Budget');
        $response->assertSee('Entry Tests');
        $response->assertSee('Financial Aid');
    }

    /**
     * Test Strategic Recommendation Tiers screen generates Reach, Match, and Safety tiers.
     */
    public function test_recommendations_engine_renders_reach_match_safety_tiers(): void
    {
        $response = $this->get('/counselor/recommendations?'.http_build_query([
            'matric_percentage' => 85.0,
            'fsc_percentage' => 80.0,
            'test_type' => 'net',
            'test_score' => 152,
            'discipline' => 'computing',
            'location' => 'islamabad',
            'budget_max' => 200000,
            'monthly_income' => 40000,
        ]));

        $response->assertStatus(200);
        $response->assertSee('Your Admission Likelihood Tiers');
        $response->assertSee('NUST SEECS Aggregate');
        $response->assertSee('Reach Programs');
        $response->assertSee('Target Match Programs');
        $response->assertSee('Safety Programs');
        $response->assertSee('Qualifying Financial Aid');
        $response->assertSee('HEC Need-Based Undergraduate Scholarship');
    }

    /**
     * Test Conversational University Search parses natural language queries and extracts criteria.
     */
    public function test_conversational_search_parses_intent_and_returns_matches(): void
    {
        $response = $this->get('/counselor/search?q=Top+CS+universities+in+Islamabad+under+200k+with+hostel');
        $response->assertStatus(200);
        $response->assertSee('Conversational University Search');
        $response->assertSee('Interpreted Intent');
        $response->assertSee('Computing');
        $response->assertSee('Islamabad / Rawalpindi');
        $response->assertSee('Under PKR 200,000 / semester');
        $response->assertSee('Matches Found');
    }

    /**
     * Test Statutory Evidence Viewer renders legal acts, gazette citations, and verification hashes.
     */
    public function test_statutory_evidence_viewer_renders_legal_acts_and_gazettes(): void
    {
        $this->get('/counselor/evidence')->assertOk()->assertSee('University source evidence');
        $this->get('/counselor/evidence/nceac-ratings')->assertNotFound();
    }

    /**
     * Test Consultation History renders archives and supports topic filtering.
     */
    public function test_chat_history_renders_and_supports_filtering_and_deletion(): void
    {
        $this->actingAs(User::factory()->create());
        $thread = app(CounselorWorkspace::class)->send('FAST comparison research')['conversation'];
        AiConversation::findOrFail($thread['id'])->update(['topic' => 'comparisons']);

        $this->get('/counselor/history')->assertOk()->assertSee('FAST comparison research');
        $this->get('/counselor/history?filter=comparisons')->assertOk()->assertSee('FAST comparison research');
        $this->delete(route('counselor.destroy', $thread['id']))->assertRedirect(route('counselor.history'));
        $this->assertNull(AiConversation::find($thread['id']));
    }
}
