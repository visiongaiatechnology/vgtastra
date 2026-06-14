<?php

declare(strict_types=1);

// STATUS: DIAMANT VGT SUPREME

namespace VGTAstra\AgentSystem;

if (!defined('ABSPATH')) {
    exit;
}

final class AgenticOrchestrator
{
    private const OPTION_KEY_API_KEY = 'vgta_groq_api_key_vault';
    private const NONCE_ACTION = 'vgta_agentic_nonce';
    private const MENU_SLUG = 'vgta-agent-system';
    private const API_KEY_CONTEXT = 'groq:primary-api-key:v1';
    private const MAX_SCANNED_FILE_BYTES = 153600;
    private const MAX_AGENT_STEPS = 8;
    private const MAX_HISTORY_MESSAGES = 36;
    private const MAX_LEDGER_ENTRIES = 40;
    private const MAX_CHAT_BYTES = 8000;
    private const MAX_LEDGER_ENTRY_BYTES = 6000;
    private const MAX_WRITE_BYTES = 786432;
    private const MAX_CONTEXT_PACK_BYTES = 220000;
    private const MAX_CONTEXT_FILE_BYTES = 70000;
    private const ERROR_CODE_CONTEXT = 'vgta-error-code:v1';
    private const WORKSPACE_DIR_NAME = 'vgta-agent-workspace';
    private const WORKSPACE_CONTEXT = 'vgta-secure-workspace:v1';
    private const PATCH_VAULT_CONTEXT = 'vgta-encrypted-patch-vault:v1';
    private const REVIEW_TOKEN_CONTEXT = 'vgta-review-token:v1';
    private const MEMORY_DIR_NAME = 'feed_cafe_0000_1111';
    private const MEMORY_CONTEXT = 'vgta-memory-store:v1';
    private const MAX_MEMORY_SESSIONS = 30;
    private const MAX_MEMORY_MESSAGES = 80;
    private const MAX_MEMORY_ARTIFACTS = 120;
    private const MAX_ARTIFACT_BYTES = 12000;

    /**
     * @var array<string, array{label:string,max_output:int,multimodal:bool,reasoning_values:list<string>,reasoning_default:string}>
     */
    private const GROQ_MODELS = [
        'openai/gpt-oss-120b' => [
            'label' => 'GPT OSS 120B',
            'max_output' => 65536,
            'multimodal' => false,
            'reasoning_values' => ['low', 'medium', 'high'],
            'reasoning_default' => 'high',
        ],
        'qwen/qwen3-32b' => [
            'label' => 'Qwen 3 32B',
            'max_output' => 40960,
            'multimodal' => false,
            'reasoning_values' => ['default', 'none'],
            'reasoning_default' => 'default',
        ],
        'meta-llama/llama-4-scout-17b-16e-instruct' => [
            'label' => 'Llama 4 Scout 17B 16E',
            'max_output' => 8192,
            'multimodal' => true,
            'reasoning_values' => [],
            'reasoning_default' => 'none',
        ],
        'openai/gpt-oss-20b' => [
            'label' => 'GPT OSS 20B',
            'max_output' => 65536,
            'multimodal' => false,
            'reasoning_values' => ['low', 'medium', 'high'],
            'reasoning_default' => 'high',
        ],
    ];

    /**
     * @var array<string, string>
     */
    private const ROLE_PROMPTS = [
        'Architect' => 'You are the Architect. You never write production code and never emit FILE_WRITE. Produce architecture, dependency boundaries, risk map, ordered implementation directives, and acceptance criteria. Your output must be actionable for the Developer.',
        'Developer' => 'You are the Developer. Convert the Architect plan into concrete files. Emit complete file replacements only through FILE_WRITE: relative/path.ext followed by one fenced code block. Explain decisions after the file payloads. Do not modify active plugins.',
        'Auditor' => 'You are the Auditor. Red-team the Developer output. Verify security, WordPress escaping, nonce/capability checks, path safety, and runtime regressions. Output PIPELINE_STATUS: APPROVED only when no blocking issue remains. If fixes are required, emit PIPELINE_STATUS: NEEDS_REVISION with exact corrections.',
        'Integrator' => 'You are the Integrator. Reconcile Architect, Developer, and Auditor outputs into a final patch strategy. Emit FILE_WRITE only when the final integration requires concrete file content.',
        'Assistant' => 'You are the live VGTAstra engineering assistant. Discuss the current plugin, answer operator questions, refine instructions, and prepare the next pipeline run. Do not emit FILE_WRITE unless explicitly asked to draft a patch.',
    ];

    use RuntimeTrait;
    use AjaxActionsTrait;
    use PluginContextTrait;
    use PatchVaultTrait;
    use PatchReviewTrait;
    use MemoryStoreTrait;
    use ValidationTrait;
    use GroqGatewayTrait;
}
