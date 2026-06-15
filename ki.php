<?php
/**
 * Plugin Name: VGTAstra Agent System
 * Description: Zero-dependency WordPress AI assistant system with live chat, Groq reasoning pipelines, encrypted vault storage, and safe plugin patch staging.
 * Version: 1.3.0-beta.5
 * Author: VisionGaia Technology
 * License: Proprietary
 */

declare(strict_types=1);

// STATUS: DIAMANT VGT SUPREME

if (!defined('ABSPATH')) {
    exit;
}

define('VGTA_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('VGTA_PLUGIN_URL', plugin_dir_url(__FILE__));
define('VGTA_PLUGIN_VERSION', '1.3.0-beta.5');

require_once VGTA_PLUGIN_DIR . 'includes/class-vgta-exceptions.php';
require_once VGTA_PLUGIN_DIR . 'includes/class-vgta-crypto-vault.php';
require_once VGTA_PLUGIN_DIR . 'includes/class-vgta-registry.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-runtime.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-ajax-actions.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-plugin-context.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-patch-repair.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-patch-vault.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-patch-review.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-memory-store.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-repair-runtime.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-validation.php';
require_once VGTA_PLUGIN_DIR . 'includes/trait-vgta-groq.php';
require_once VGTA_PLUGIN_DIR . 'includes/class-vgta-orchestrator.php';

new \VGTAstra\AgentSystem\AgenticOrchestrator();
