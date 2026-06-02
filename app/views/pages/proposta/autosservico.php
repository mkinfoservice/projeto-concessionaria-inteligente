<?php
/**
 * Public self-service proposal form.
 */

$planosPorOperadora = [];
$operadorasPorId = [];
foreach ($operadoras as $op) {
    $operadorasPorId[(int)$op['id']] = $op;
}
foreach ($planos as $p) {
    $planosPorOperadora[$p['operadora_id']][] = $p;
}

$success = !empty($protocolo) && empty($errors) && $_SERVER['REQUEST_METHOD'] !== 'POST';
?>

<section class="self-service-hero" aria-label="Solicitacao de plano">
    <div class="self-service-shell">
        <div class="self-service-hero-grid">
            <div class="self-service-copy">
                <div class="hero-badge">
                    <span class="dot"></span>
                    Autosservico seguro
                </div>
                <h1>Solicite seu plano em poucos minutos.</h1>
                <p>
                    Preencha seus dados, escolha a operadora e envie os documentos. Voce pode informar o telefone
                    de um vendedor credenciado para direcionar a comissao.
                </p>
                <div class="self-service-trust">
                    <span>Sem login obrigatorio</span>
                    <span>Fila de analise oficial</span>
                    <span>Indicacao opcional</span>
                </div>
            </div>

            <aside class="self-service-side-card">
                <span class="eyebrow">Como funciona</span>
                <ol>
                    <li>Informe seus dados e endereco.</li>
                    <li>Escolha o plano com apoio do comparador.</li>
                    <li>Envie os documentos obrigatorios.</li>
                    <li>A equipe analisa e retorna com o andamento.</li>
                </ol>
            </aside>
        </div>
    </div>
</section>

<section class="self-service-content">
    <div class="self-service-shell">
        <?php if ($success): ?>
            <div class="self-service-success">
                <div class="success-icon">✓</div>
                <span class="eyebrow">Solicitacao recebida</span>
                <h2>Proposta enviada com sucesso.</h2>
                <p>Guarde seu protocolo para acompanhar o atendimento:</p>
                <strong><?= h((string)$protocolo) ?></strong>
                <div class="success-actions">
                    <a href="/" class="btn btn-secondary">Voltar ao inicio</a>
                    <a href="/solicitar-plano" class="btn btn-primary">Enviar outra solicitacao</a>
                </div>
            </div>
        <?php else: ?>
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger self-service-alert">
                    <div>
                        <strong>Confira os dados antes de continuar:</strong>
                        <ul>
                            <?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>

            <div class="progress-steps self-service-stepper" id="self-service-stepper">
                <div class="progress-step active" data-step-indicator="1">
                    <div class="progress-step-num">1</div>
                    <span class="progress-step-label">Indicacao</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step-indicator="2">
                    <div class="progress-step-num">2</div>
                    <span class="progress-step-label">Dados</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step-indicator="3">
                    <div class="progress-step-num">3</div>
                    <span class="progress-step-label">Plano</span>
                </div>
                <div class="progress-connector"></div>
                <div class="progress-step" data-step-indicator="4">
                    <div class="progress-step-num">4</div>
                    <span class="progress-step-label">Documentos</span>
                </div>
            </div>

            <div class="self-service-grid">
                <form method="POST" action="/solicitar-plano" enctype="multipart/form-data" id="self-service-form" class="self-service-form" novalidate>
                    <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                    <input type="hidden" name="idades_vidas" id="idades_vidas" value="<?= h($input['idades_vidas'] ?? '') ?>">
                    <input type="text" name="website" tabindex="-1" autocomplete="off" class="hp-field" aria-hidden="true">

                    <div class="self-step active" data-step="1">
                        <div class="self-card">
                            <div class="self-card-head">
                                <div class="form-section-icon">1</div>
                                <div>
                                    <span class="eyebrow">Indicacao</span>
                                    <h2>Voce foi indicado por alguem?</h2>
                                    <p>Informe o telefone do vendedor credenciado. Se deixar vazio, a proposta segue normalmente sem repasse individual.</p>
                                </div>
                            </div>

                            <div class="referral-box">
                                <label class="form-label" for="indicado_por">Indicado por</label>
                                <input type="text" id="indicado_por" name="indicado_por" class="form-input"
                                       data-mask="phone" value="<?= h($input['indicado_por'] ?? '') ?>"
                                       placeholder="(21) 99999-9999">
                                <small class="form-hint">Somente vendedores ativos podem receber a comissao.</small>
                            </div>

                            <div class="self-note">
                                <strong>Sem indicacao?</strong>
                                A proposta continua valendo. A comissao fica como margem da empresa e nao aparece no painel de vendedores.
                            </div>
                        </div>
                    </div>

                    <div class="self-step" data-step="2">
                        <div class="self-card">
                            <div class="self-card-head">
                                <div class="form-section-icon">2</div>
                                <div>
                                    <span class="eyebrow">Beneficiario</span>
                                    <h2>Dados do titular</h2>
                                    <p>Use os mesmos dados que serao enviados para analise da proposta.</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="nome_completo">Nome completo <span class="required">*</span></label>
                                    <input type="text" id="nome_completo" name="nome_completo" class="form-input" value="<?= h($input['nome_completo'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="cpf">CPF <span class="required">*</span></label>
                                    <input type="text" id="cpf" name="cpf" class="form-input" data-mask="cpf" value="<?= h($input['cpf'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="data_nascimento">Data de nascimento <span class="required">*</span></label>
                                    <input type="date" id="data_nascimento" name="data_nascimento" class="form-input" value="<?= h($input['data_nascimento'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="telefone">Telefone <span class="required">*</span></label>
                                    <input type="text" id="telefone" name="telefone" class="form-input" data-mask="phone" value="<?= h($input['telefone'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="email">E-mail</label>
                                    <input type="email" id="email" name="email" class="form-input" value="<?= h($input['email'] ?? '') ?>" placeholder="voce@email.com">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="nome_mae">Nome da mae</label>
                                    <input type="text" id="nome_mae" name="nome_mae" class="form-input" value="<?= h($input['nome_mae'] ?? '') ?>">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="rg">RG</label>
                                    <input type="text" id="rg" name="rg" class="form-input" value="<?= h($input['rg'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="profissao">Profissao</label>
                                    <input type="text" id="profissao" name="profissao" class="form-input" value="<?= h($input['profissao'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <div class="self-card">
                            <div class="self-card-head compact">
                                <div class="form-section-icon">⌂</div>
                                <div>
                                    <h2>Endereco</h2>
                                    <p>O CEP ajuda a conferir cobertura e rede regional.</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="cep">CEP <span class="required">*</span></label>
                                    <input type="text" id="cep" name="cep" class="form-input" data-mask="cep" data-cep-autofill value="<?= h($input['cep'] ?? '') ?>" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="endereco">Endereco <span class="required">*</span></label>
                                    <input type="text" id="endereco" name="endereco" class="form-input" value="<?= h($input['endereco'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="bairro">Bairro</label>
                                    <input type="text" id="bairro" name="bairro" class="form-input" value="<?= h($input['bairro'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="cidade">Cidade <span class="required">*</span></label>
                                    <input type="text" id="cidade" name="cidade" class="form-input" value="<?= h($input['cidade'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="form-row compact-row">
                                <div class="form-group">
                                    <label class="form-label" for="numero">Numero</label>
                                    <input type="text" id="numero" name="numero" class="form-input" value="<?= h($input['numero'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="complemento">Complemento</label>
                                    <input type="text" id="complemento" name="complemento" class="form-input" value="<?= h($input['complemento'] ?? '') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="uf">UF <span class="required">*</span></label>
                                    <select id="uf" name="uf" class="form-input" required>
                                        <option value="">--</option>
                                        <?php foreach (['AC','AL','AP','AM','BA','CE','DF','ES','GO','MA','MT','MS','MG','PA','PB','PR','PE','PI','RJ','RN','RS','RO','RR','SC','SP','SE','TO'] as $uf): ?>
                                            <option value="<?= $uf ?>" <?= ($input['uf'] ?? '') === $uf ? 'selected' : '' ?>><?= $uf ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="self-step" data-step="3">
                        <div class="self-card">
                            <div class="self-card-head">
                                <div class="form-section-icon">3</div>
                                <div>
                                    <span class="eyebrow">Plano</span>
                                    <h2>Escolha sua melhor opcao</h2>
                                    <p>Filtre por operadora e categoria. O valor exibido considera a idade do titular e quantidade de vidas.</p>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="operadora_id">Operadora <span class="required">*</span></label>
                                    <select id="operadora_id" name="operadora_id" class="form-input" required>
                                        <option value="">Todas as operadoras</option>
                                        <?php foreach ($operadoras as $op): ?>
                                            <option value="<?= (int)$op['id'] ?>" <?= (string)($input['operadora_id'] ?? '') === (string)$op['id'] ? 'selected' : '' ?>><?= h($op['nome']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="categoria">Categoria <span class="required">*</span></label>
                                    <select id="categoria" name="categoria" class="form-input" required>
                                        <option value="">Todas</option>
                                        <option value="SEM_COPARTICIPACAO" <?= ($input['categoria'] ?? '') === 'SEM_COPARTICIPACAO' ? 'selected' : '' ?>>Sem coparticipacao</option>
                                        <option value="COM_COPARTICIPACAO" <?= ($input['categoria'] ?? '') === 'COM_COPARTICIPACAO' ? 'selected' : '' ?>>Com coparticipacao</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="quantidade_vidas">Quantidade de vidas</label>
                                    <input type="number" min="1" max="20" id="quantidade_vidas" name="quantidade_vidas" class="form-input" value="<?= h($input['quantidade_vidas'] ?? '1') ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="plan-search">Buscar plano</label>
                                    <input type="text" id="plan-search" class="form-input" placeholder="Nome, cobertura ou operadora">
                                </div>
                            </div>

                            <select id="plano_id" name="plano_id" class="sr-only-select" required>
                                <option value="">Selecione</option>
                                <?php foreach ($planos as $plano): ?>
                                    <option value="<?= (int)$plano['id'] ?>" <?= (string)($input['plano_id'] ?? '') === (string)$plano['id'] ? 'selected' : '' ?>>
                                        <?= h($plano['nome']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>

                            <div class="self-plan-results" id="self-plan-results" aria-live="polite"></div>
                            <div class="self-empty-plans" id="self-empty-plans" hidden>Nenhum plano encontrado para os filtros selecionados.</div>

                            <div class="form-group">
                                <label class="form-label" for="observacoes">Observacoes</label>
                                <textarea id="observacoes" name="observacoes" rows="3" class="form-input" placeholder="Alguma necessidade especifica?"><?= h($input['observacoes'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="self-step" data-step="4">
                        <div class="self-card">
                            <div class="self-card-head">
                                <div class="form-section-icon">4</div>
                                <div>
                                    <span class="eyebrow">Documentos</span>
                                    <h2>Envie os arquivos para analise</h2>
                                    <p>Formatos aceitos: PDF, JPG e PNG. A frente do documento e obrigatoria.</p>
                                </div>
                            </div>

                            <div class="self-upload-grid">
                                <?php
                                $docs = [
                                    ['doc_frente', 'Documento de identidade - frente', true],
                                    ['doc_verso', 'Documento de identidade - verso', false],
                                    ['doc_residencia', 'Comprovante de residencia', false],
                                    ['doc_contracheque', 'Comprovante de renda', false],
                                ];
                                foreach ($docs as [$name, $label, $required]):
                                ?>
                                    <label class="self-upload-card" for="<?= h($name) ?>">
                                        <span><?= h($label) ?><?= $required ? ' *' : '' ?></span>
                                        <small data-file-label="<?= h($name) ?>">Clique para selecionar</small>
                                        <input type="file" id="<?= h($name) ?>" name="<?= h($name) ?>" accept=".pdf,.jpg,.jpeg,.png" <?= $required ? 'required' : '' ?>>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="self-service-actions">
                        <button type="button" class="btn btn-secondary" id="self-prev" hidden>Voltar</button>
                        <button type="button" class="btn btn-primary" id="self-next">Continuar</button>
                        <button type="submit" class="btn btn-primary" id="self-submit" hidden>Enviar solicitacao</button>
                    </div>
                </form>

                <aside class="self-summary" aria-label="Resumo da solicitacao">
                    <div class="summary-card">
                        <span class="eyebrow">Resumo</span>
                        <h3 id="summary-plan">Nenhum plano selecionado</h3>
                        <p id="summary-operator">Escolha uma opcao para ver o resumo.</p>
                        <div class="summary-price" id="summary-price">R$ 0,00</div>
                        <dl>
                            <div>
                                <dt>Vidas</dt>
                                <dd id="summary-lives">1</dd>
                            </div>
                            <div>
                                <dt>Titular</dt>
                                <dd id="summary-holder">Aguardando dados</dd>
                            </div>
                            <div>
                                <dt>Indicacao</dt>
                                <dd id="summary-referral">Opcional</dd>
                            </div>
                        </dl>
                    </div>
                </aside>
            </div>
        <?php endif; ?>
    </div>
</section>

<script>
const selfServicePlans = <?= json_encode($planos, JSON_UNESCAPED_UNICODE) ?>;
const selfServiceOperators = <?= json_encode($operadorasPorId, JSON_UNESCAPED_UNICODE) ?>;
let selfStep = 1;
let selectedPlanId = document.getElementById('plano_id')?.value || '';

function setSelfStep(step) {
    selfStep = Math.max(1, Math.min(4, step));
    document.querySelectorAll('.self-step').forEach((el) => {
        el.classList.toggle('active', Number(el.dataset.step) === selfStep);
    });
    document.querySelectorAll('[data-step-indicator]').forEach((el) => {
        const n = Number(el.dataset.stepIndicator);
        el.classList.toggle('active', n === selfStep);
        el.classList.toggle('done', n < selfStep);
    });
    document.querySelectorAll('.self-service-stepper .progress-connector').forEach((el, index) => {
        el.classList.toggle('done', index + 1 < selfStep);
    });
    document.getElementById('self-prev').hidden = selfStep === 1;
    document.getElementById('self-next').hidden = selfStep === 4;
    document.getElementById('self-submit').hidden = selfStep !== 4;
    window.scrollTo({ top: document.querySelector('.self-service-content').offsetTop - 80, behavior: 'smooth' });
}

function fieldsForStep(step) {
    const el = document.querySelector(`.self-step[data-step="${step}"]`);
    return [...el.querySelectorAll('input, select, textarea')].filter((field) => {
        return !field.disabled
            && field.type !== 'hidden'
            && !field.classList.contains('sr-only-select');
    });
}

function validateCurrentStep() {
    for (const field of fieldsForStep(selfStep)) {
        if (field.type === 'file' && field.required && !field.files.length) {
            CIB.toast('Envie o documento obrigatorio para continuar.', 'warning');
            field.closest('.self-upload-card')?.classList.add('needs-file');
            return false;
        }
        if (!field.checkValidity()) {
            field.reportValidity();
            return false;
        }
    }
    if (selfStep === 3 && !selectedPlanId) {
        CIB.toast('Selecione um plano para continuar.', 'warning');
        return false;
    }
    return true;
}

function holderAge() {
    const raw = document.getElementById('data_nascimento')?.value;
    if (!raw) return 35;
    const birth = new Date(raw + 'T00:00:00');
    const today = new Date();
    let age = today.getFullYear() - birth.getFullYear();
    const m = today.getMonth() - birth.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
    return Math.max(0, age || 0);
}

function priceColumn(age) {
    if (age <= 18) return 'faixa_0_18';
    if (age <= 28) return 'faixa_19_28';
    if (age <= 43) return 'faixa_29_43';
    if (age <= 58) return 'faixa_44_58';
    return 'faixa_59_plus';
}

function formatCurrency(value) {
    return 'R$ ' + Number(value || 0).toLocaleString('pt-BR', { minimumFractionDigits: 2 });
}

function planBasePrice(plan) {
    return Number(plan[priceColumn(holderAge())] || 0);
}

function planTotal(plan) {
    const lives = Math.max(1, Number(document.getElementById('quantidade_vidas')?.value || 1));
    return planBasePrice(plan) * lives;
}

function filteredPlans() {
    const operator = document.getElementById('operadora_id')?.value || '';
    const category = document.getElementById('categoria')?.value || '';
    const search = (document.getElementById('plan-search')?.value || '').toLowerCase();
    return selfServicePlans.filter((plan) => {
        const op = selfServiceOperators[plan.operadora_id] || {};
        const hay = `${plan.nome} ${plan.cobertura || ''} ${op.nome || ''}`.toLowerCase();
        return (!operator || String(plan.operadora_id) === String(operator))
            && (!category || plan.categoria === category)
            && (!search || hay.includes(search));
    });
}

function selectPlan(id) {
    selectedPlanId = String(id);
    document.getElementById('plano_id').value = selectedPlanId;
    renderPlans();
    updateSummary();
}

function renderPlans() {
    const wrap = document.getElementById('self-plan-results');
    const empty = document.getElementById('self-empty-plans');
    if (!wrap) return;
    const plans = filteredPlans();
    empty.hidden = plans.length > 0;
    wrap.innerHTML = plans.map((plan) => {
        const op = selfServiceOperators[plan.operadora_id] || {};
        const selected = String(plan.id) === String(selectedPlanId);
        return `
            <button type="button" class="self-plan-card ${selected ? 'selected' : ''}" onclick="selectPlan(${Number(plan.id)})">
                <span>${escapeHtml(op.nome || 'Operadora')}</span>
                <strong>${escapeHtml(plan.nome)}</strong>
                <small>${escapeHtml(plan.cobertura || 'Cobertura conforme contrato')} · ${plan.categoria === 'COM_COPARTICIPACAO' ? 'Com coparticipacao' : 'Sem coparticipacao'}</small>
                <em>${formatCurrency(planTotal(plan))}</em>
            </button>
        `;
    }).join('');
}

function escapeHtml(value) {
    return String(value || '').replace(/[&<>"']/g, (char) => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#039;'}[char]));
}

function updateSummary() {
    const plan = selfServicePlans.find((item) => String(item.id) === String(selectedPlanId));
    const op = plan ? selfServiceOperators[plan.operadora_id] || {} : {};
    document.getElementById('summary-plan').textContent = plan ? plan.nome : 'Nenhum plano selecionado';
    document.getElementById('summary-operator').textContent = plan ? `${op.nome || 'Operadora'} · ${plan.cobertura || 'Cobertura informada'}` : 'Escolha uma opcao para ver o resumo.';
    document.getElementById('summary-price').textContent = plan ? formatCurrency(planTotal(plan)) : 'R$ 0,00';
    document.getElementById('summary-lives').textContent = document.getElementById('quantidade_vidas')?.value || '1';
    document.getElementById('summary-holder').textContent = document.getElementById('nome_completo')?.value || 'Aguardando dados';
    document.getElementById('summary-referral').textContent = document.getElementById('indicado_por')?.value || 'Sem indicacao';
}

document.getElementById('self-next')?.addEventListener('click', () => {
    if (validateCurrentStep()) setSelfStep(selfStep + 1);
});
document.getElementById('self-prev')?.addEventListener('click', () => setSelfStep(selfStep - 1));
document.getElementById('self-service-form')?.addEventListener('submit', (event) => {
    if (!validateCurrentStep()) {
        event.preventDefault();
        return;
    }
    const btn = document.getElementById('self-submit');
    btn.disabled = true;
    btn.textContent = 'Enviando...';
});

['operadora_id','categoria','plan-search','quantidade_vidas','data_nascimento'].forEach((id) => {
    document.getElementById(id)?.addEventListener('input', () => { renderPlans(); updateSummary(); });
    document.getElementById(id)?.addEventListener('change', () => { renderPlans(); updateSummary(); });
});
['nome_completo','indicado_por'].forEach((id) => {
    document.getElementById(id)?.addEventListener('input', updateSummary);
});
document.querySelectorAll('.self-upload-card input[type="file"]').forEach((input) => {
    input.addEventListener('change', () => {
        const label = document.querySelector(`[data-file-label="${input.id}"]`);
        if (label) label.textContent = input.files[0]?.name || 'Clique para selecionar';
        input.closest('.self-upload-card')?.classList.toggle('has-file', Boolean(input.files[0]));
        input.closest('.self-upload-card')?.classList.remove('needs-file');
    });
});

renderPlans();
updateSummary();

<?php if (!empty($errors)): ?>
(function () {
    const errorKeys = <?= json_encode(array_keys($errors), JSON_UNESCAPED_UNICODE) ?>;
    const map = {
        indicado_por: 1,
        nome_completo: 2, cpf: 2, data_nascimento: 2, telefone: 2, cep: 2, endereco: 2, cidade: 2, uf: 2,
        operadora_id: 3, plano_id: 3, categoria: 3,
        doc_frente: 4, doc_verso: 4, doc_residencia: 4, doc_contracheque: 4,
    };
    const target = errorKeys.reduce((step, key) => Math.min(step, map[key] || step), 4);
    setSelfStep(target);
})();
<?php endif; ?>
</script>
