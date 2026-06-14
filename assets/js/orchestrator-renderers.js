/**
 * STATUS: DIAMANT VGT SUPREME
 * VGTAstra DOM-safe renderers.
 */

window.VGTAstraRenderers = (() => {
    'use strict';

    function renderStructuralMap(mapData, nodes, createTextElement) {
        nodes.mapTree.replaceChildren();
        const entries = Object.entries(mapData || {});
        if (entries.length === 0) {
            nodes.mapTree.appendChild(createTextElement('div', 'vgta-placeholder-text', 'No indexed files found.'));
            return;
        }

        entries.forEach(([filepath, details]) => {
            const item = document.createElement('div');
            item.className = 'vgta-tree-item';
            if (details.is_primary) {
                item.classList.add('primary-file');
            }

            const summaryElement = document.createElement('div');
            summaryElement.className = 'vgta-tree-item-summary';
            summaryElement.appendChild(createTextElement('span', 'path', filepath));

            const badges = document.createElement('div');
            badges.className = 'meta-badges';
            if (details.too_large) {
                badges.appendChild(createTextElement('span', 'vgta-file-badge danger', 'SKIPPED'));
            } else {
                badges.appendChild(createTextElement('span', 'vgta-file-badge', `${(Number(details.size || 0) / 1024).toFixed(1)} KB`));
                const hookCount = Array.isArray(details.registered_hooks) ? details.registered_hooks.length : 0;
                const classCount = Array.isArray(details.classes) ? details.classes.length : 0;
                if (hookCount > 0) {
                    badges.appendChild(createTextElement('span', 'vgta-file-badge hook', `HOOKS ${hookCount}`));
                }
                if (classCount > 0) {
                    badges.appendChild(createTextElement('span', 'vgta-file-badge class', `CLASSES ${classCount}`));
                }
                if (details.security_notes && details.security_notes !== 'Secure' && details.security_notes !== 'Not audited.') {
                    badges.appendChild(createTextElement('span', 'vgta-file-badge warning', 'AUDIT'));
                }
            }

            summaryElement.appendChild(badges);
            item.appendChild(summaryElement);
            const detailsBox = document.createElement('div');
            detailsBox.className = 'vgta-tree-item-body';
            detailsBox.appendChild(createTextElement('div', 'details-label', 'PURPOSE:'));
            detailsBox.appendChild(createTextElement('div', 'details-val', details.purpose || 'No description.'));
            if (details.security_notes) {
                detailsBox.appendChild(createTextElement('div', 'details-label sec-label', 'SECURITY AUDIT:'));
                const secText = createTextElement('div', 'details-val sec-val', details.security_notes);
                if (details.security_notes !== 'Secure' && details.security_notes !== 'Not audited.') {
                    secText.classList.add('security-alert');
                }
                detailsBox.appendChild(secText);
            }
            if (Array.isArray(details.classes) && details.classes.length > 0) {
                detailsBox.appendChild(createTextElement('div', 'details-label', 'CLASSES:'));
                detailsBox.appendChild(createTextElement('div', 'details-val list', details.classes.join(', ')));
            }
            if (Array.isArray(details.hooks) && details.hooks.length > 0) {
                detailsBox.appendChild(createTextElement('div', 'details-label', 'HOOKS:'));
                detailsBox.appendChild(createTextElement('div', 'details-val list', details.hooks.join(', ')));
            }
            item.appendChild(detailsBox);
            nodes.mapTree.appendChild(item);
        });
    }

    function appendPlainMessage(kind, meta, content, nodes, createTextElement) {
        const box = document.createElement('div');
        box.className = `vgta-message ${kind}`;
        box.appendChild(createTextElement('div', 'vgta-message-meta', meta));
        const body = document.createElement('div');
        body.className = 'vgta-message-body';
        const contentDiv = document.createElement('div');
        contentDiv.className = 'vgta-response-content';
        renderMarkdownSafely(String(content), contentDiv, createTextElement);
        body.appendChild(contentDiv);
        box.appendChild(body);
        nodes.chatLog.appendChild(box);
        nodes.chatLog.scrollTop = nodes.chatLog.scrollHeight;
    }

    function appendRichAssistantMessage(role, model, content, reasoning, nodes, createTextElement) {
        const box = document.createElement('div');
        box.className = 'vgta-message assistant';
        box.appendChild(createTextElement('div', 'vgta-message-meta', `${role} - ${model}`));
        const body = document.createElement('div');
        body.className = 'vgta-message-body';
        if (reasoning) {
            const details = document.createElement('details');
            details.className = 'vgta-thinking-collapse';
            const summary = document.createElement('summary');
            summary.textContent = 'SYSTEM BRAIN ENGINE / REASONING';
            details.appendChild(summary);
            const thinkingDiv = document.createElement('div');
            thinkingDiv.className = 'vgta-thinking-content';
            thinkingDiv.textContent = reasoning;
            details.appendChild(thinkingDiv);
            body.appendChild(details);
        }
        const responseDiv = document.createElement('div');
        responseDiv.className = 'vgta-response-content';
        renderMarkdownSafely(String(content), responseDiv, createTextElement);
        body.appendChild(responseDiv);
        box.appendChild(body);
        nodes.chatLog.appendChild(box);
        nodes.chatLog.scrollTop = nodes.chatLog.scrollHeight;
    }

    function renderMarkdownSafely(text, container, createTextElement) {
        const lines = text.split('\n');
        let inCodeBlock = false;
        let codeLines = [];
        lines.forEach((line) => {
            if (line.trim().startsWith('```')) {
                if (inCodeBlock) {
                    appendSafeCodeBlock(container, codeLines.join('\n'));
                    codeLines = [];
                    inCodeBlock = false;
                } else {
                    inCodeBlock = true;
                }
                return;
            }
            if (inCodeBlock) {
                codeLines.push(line);
                return;
            }
            if (line.trim() !== '') {
                container.appendChild(createTextElement('p', 'vgta-chat-text', line));
            }
        });
        if (inCodeBlock && codeLines.length > 0) {
            appendSafeCodeBlock(container, codeLines.join('\n'));
        }
    }

    function appendSafeCodeBlock(container, codeText) {
        const pre = document.createElement('pre');
        pre.className = 'vgta-code-container';
        const code = document.createElement('code');
        code.textContent = codeText;
        pre.appendChild(code);
        container.appendChild(pre);
    }

    return { renderStructuralMap, appendPlainMessage, appendRichAssistantMessage };
})();
