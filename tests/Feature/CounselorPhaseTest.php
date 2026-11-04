<?php

namespace Tests\Feature;

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
        $response->assertSee('UniGuide National AI Admission Counselor');
        $response->assertSee('Intelligent admission guidance');
        $response->assertSee('Guided Merit');
        $response->assertSee('Personalized Reach, Match');
        $response->assertSee('Statutory Grounding');
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

        // Seeded conversation thread
        $responseConv = $this->get('/counselor/chat/conv-1');
        $responseConv->assertStatus(200);
        $responseConv->assertSee('BS Computer Science in Islamabad under PKR 200k');
        $responseConv->assertSee('COMSATS University Islamabad');
        $responseConv->assertSee('National University of Sciences & Technology', false);
    }

    /**
     * Test sending a message through the POST endpoint (both JSON and form redirect).
     */
    public function test_send_message_post_endpoint_handles_web_and_json(): void
    {
        // 1. JSON AJAX submission
        $responseJson = $this->postJson('/counselor/chat/message', [
            'message' => 'How is NUST CS aggregate calculated?',
            'conversation_id' => 'conv-1',
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
        // Main evidence index
        $response = $this->get('/counselor/evidence');
        $response->assertStatus(200);
        $response->assertSee('Statutory Evidence');
        $response->assertSee('HEC National Qualifications Framework');
        $response->assertSee('Cryptographically Verified');
        $response->assertSee('SHA-256 Match');

        // Specific evidence slug
        $responseItem = $this->get('/counselor/evidence/nceac-ratings');
        $responseItem->assertStatus(200);
        $responseItem->assertSee('NCEAC National Computing Education Accreditation Council Register');
        $responseItem->assertSee('Category W: NUST, FAST Islamabad/Lahore, and COMSATS');
    }

    /**
     * Test Consultation History renders archives and supports topic filtering.
     */
    public function test_chat_history_renders_and_supports_filtering_and_deletion(): void
    {
        $response = $this->get('/counselor/history');
        $response->assertStatus(200);
        $response->assertSee('Consultation History');
        $response->assertSee('All Topics');
        $response->assertSee('BS Computer Science in Islamabad under PKR 200k');

        // Filter by topic
        $responseTopic = $this->get('/counselor/history?filter=comparisons');
        $responseTopic->assertStatus(200);
        $responseTopic->assertSee('FAST vs NUST Software Engineering Comparison');

        // Delete action
        $responseDelete = $this->delete('/counselor/history/conv-1');
        $responseDelete->assertStatus(302);
        $responseDelete->assertRedirect(route('counselor.history'));
    }
}
