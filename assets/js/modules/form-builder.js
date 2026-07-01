// @ts-check
// GACL Intranet — Form Builder (v2)
// Comprehensive section-based form builder with branching / skip logic.
// Non-technical HR / ICT staff can create, edit and manage forms
// without writing code.

/* ══════════════════════════════════════════════════════
   DATA MODEL
   ══════════════════════════════════════════════════════
  CustomForm {
    id            string
    name          string
    description   string
    category      'personal'|'claims'|'safety'|'admin'|'other'
    departments   string[]          which departments see this form
    routingOffice string
    routingEmail  string
    enabled       boolean
    createdBy     string
    createdAt     string            ISO date
    sections      Section[]
  }

  Section {
    id        string
    title     string
    fields    FormField[]
    condition Condition|null        null = always visible
  }

  Condition {
    fieldId   string               id of a field in a previous section
    operator  'eq'|'neq'|'contains'|'not_contains'|'filled'|'empty'
    value     string               (not used for 'filled'/'empty')
  }

  FormField {
    id          string
    type        string
    label       string
    placeholder string
    required    boolean
    options     string[]           only for 'select'|'radio'|'checkbox_multi'
  }
   ══════════════════════════════════════════════════════ */

const FB_KEY = 'gacl_custom_forms_v2';

const FB_FIELD_TYPES = [
  { value:'text',           label:'Short text',               icon:'✏️' },
  { value:'textarea',       label:'Long text / paragraph',    icon:'📝' },
  { value:'number',         label:'Number',                   icon:'🔢' },
  { value:'email',          label:'Email address',            icon:'📧' },
  { value:'tel',            label:'Phone / extension',        icon:'📞' },
  { value:'date',           label:'Date',                     icon:'📅' },
  { value:'datetime-local', label:'Date & time',              icon:'🕐' },
  { value:'select',         label:'Dropdown (choose one)',    icon:'🔽' },
  { value:'radio',          label:'Multiple choice',          icon:'⭕' },
  { value:'checkbox_multi', label:'Checkboxes (choose many)', icon:'☑️' },
  { value:'checkbox',       label:'Single checkbox (yes/no)', icon:'✔️' },
  { value:'file_info',      label:'File upload note',         icon:'📎' },
];

const FB_CATEGORIES = [
  { value:'personal', label:'Personal Services' },
  { value:'claims',   label:'Claims & Allowances' },
  { value:'safety',   label:'Safety & Welfare' },
  { value:'admin',    label:'HR Administration' },
  { value:'other',    label:'General / Other' },
];

const FB_ROUTING = [
  { label:'HCOS — HR Administration',  email:'hcos@gacl.com.gh' },
  { label:'ICT Department',             email:'ict@gacl.com.gh' },
  { label:'Finance',                    email:'finance@gacl.com.gh' },
  { label:'Safety & Environment',       email:'safety@gacl.com.gh' },
  { label:'L&D Centre',                 email:'ldc@gacl.com.gh' },
  { label:'Procurement',                email:'procurement@gacl.com.gh' },
  { label:'Legal Services',             email:'legal@gacl.com.gh' },
  { label:'Facilities & Infrastructure',email:'facilities@gacl.com.gh' },
  { label:'Aviation Security',          email:'avsec@gacl.com.gh' },
  { label:'Custom email…',              email:'custom' },
];

const FB_DEPTS = [
  "MD's Directorate","Airport Planning & Projects","Airports Management",
  "Audit, Compliance & Risk","Aviation Security","Business Development",
  "Commercial Services","Corporate Comms & PR","Facilities & Infrastructure",
  "Finance","Human Capital & OS","ICT","Legal Services","Procurement",
  "Strategy & Corporate Performance"
];

const FB_OPERATORS = [
  { value:'eq',           label:'equals' },
  { value:'neq',          label:'is not' },
  { value:'contains',     label:'contains' },
  { value:'not_contains', label:'does not contain' },
  { value:'filled',       label:'has any answer' },
  { value:'empty',        label:'is left blank' },
];

/* ── Persistence ── */
/** @returns {any[]} */
function fbGetForms() {
  try { return JSON.parse(localStorage.getItem(FB_KEY) || '[]'); } catch { return []; }
}
/** @param {any[]} forms */
function fbSaveForms(forms) { localStorage.setItem(FB_KEY, JSON.stringify(forms)); }
/** @param {any} form */
function fbSaveForm(form) {
  const forms = fbGetForms();
  const idx = forms.findIndex(f => f.id === form.id);
  if (idx >= 0) forms[idx] = form; else forms.push(form);
  fbSaveForms(forms);
}

/* ── Editor state ── */
/** @type {any} */
let _fb = null;   // form being edited (deep copy)

function _uid() { return 's' + Date.now() + Math.random().toString(36).slice(2,6); }

/* ════════════════════════════════════════════════════════════
   PAGE RENDERER
   ════════════════════════════════════════════════════════════ */
function renderFormBuilderPage() {
  const page = document.getElementById('page-form-builder');
  if (!page) return;
  page.innerHTML = _fb ? _renderEditor() : _renderList();
  if (_fb) _bindEditorLive();
}

/* ── Form list ── */
function _renderList() {
  const forms = fbGetForms();
  const catLabel = { personal:'Personal Services', claims:'Claims & Allowances', safety:'Safety & Welfare', admin:'HR Administration', other:'General' };

  if (!forms.length) return `
    <div class="page-header"><h1><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Form Builder</h1>
    <button class="btn btn-primary" onclick="fbNewForm()">+ New Form</button></div>
    <div class="card"><div class="card-body" style="text-align:center;padding:60px 20px">
      <p style="font-size:15px;font-weight:700;color:var(--color-text-primary);margin-bottom:8px">No custom forms yet</p>
      <p style="font-size:13px;color:var(--color-text-muted);margin-bottom:20px">Create forms with branching logic — no coding needed. Published forms appear instantly in HCOS Forms &amp; Services.</p>
      <button class="btn btn-primary" onclick="fbNewForm()">+ Create your first form</button>
    </div></div>`;

  return `
    <div class="page-header">
      <h1><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> Form Builder</h1>
      <button class="btn btn-primary" onclick="fbNewForm()">+ New Form</button>
    </div>
    <div class="alert alert-info" style="margin-bottom:16px">
      <strong>${forms.length} form${forms.length!==1?'s':''}</strong> — published forms appear in HCOS Forms &amp; Services for staff. Built-in forms (Leave, Medical Claim etc.) are separate and always available.
    </div>
    <div class="card"><div class="table-wrap">
      <table>
        <thead><tr><th>Form name</th><th>For departments</th><th>Category</th><th>Sections</th><th>Routes to</th><th>Status</th><th>Actions</th></tr></thead>
        <tbody>
          ${forms.map(f => {
            const sectionCount = (f.sections||[]).length;
            const fieldCount   = (f.sections||[]).reduce((s,sec) => s+(sec.fields||[]).length, 0);
            const depts = (f.departments||[]).slice(0,2).join(', ') + ((f.departments||[]).length>2?` +${f.departments.length-2} more`:'');
            return `<tr>
              <td><strong>${f.name}</strong><br><span style="font-size:11px;color:var(--color-text-muted)">${f.description||''}</span></td>
              <td style="font-size:12px">${depts||'<span style="color:var(--color-text-muted)">All staff</span>'}</td>
              <td><span class="badge badge-neutral">${catLabel[f.category]||f.category}</span></td>
              <td style="font-size:12px;font-variant-numeric:tabular-nums">${sectionCount} section${sectionCount!==1?'s':''} · ${fieldCount} field${fieldCount!==1?'s':''}</td>
              <td style="font-size:12px">${f.routingOffice}</td>
              <td><button onclick="fbToggleEnabled('${f.id}')" class="btn btn-sm" style="height:32px;background:${f.enabled?'var(--color-success-light)':'#f1f5f9'};color:${f.enabled?'var(--color-success)':'var(--color-text-muted)'}">
                ${f.enabled?'● Published':'○ Draft'}</button></td>
              <td><div style="display:flex;gap:4px">
                <button class="btn btn-sm btn-ghost" onclick="fbEditForm('${f.id}')">Edit</button>
                <button class="btn btn-sm" style="background:var(--color-danger-light);color:var(--color-danger)" onclick="fbDeleteForm('${f.id}')">Delete</button>
              </div></td>
            </tr>`;
          }).join('')}
        </tbody>
      </table>
    </div></div>`;
}

/* ── Form editor ── */
function _renderEditor() {
  const f = _fb;
  const isNew = !fbGetForms().find(x => x.id === f.id);

  return `
    <div class="page-header">
      <h1>${isNew ? 'New Form' : 'Edit Form'}</h1>
      <button class="btn btn-ghost btn-sm" onclick="fbCancelEdit()">✕ Cancel</button>
    </div>

    <!-- ① Form identity -->
    <div class="card" style="margin-bottom:14px"><div class="card-body">
      <p class="section-heading">Form details</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div class="form-group" style="margin:0">
          <label class="form-label" for="fb-name">Form name <span style="color:var(--color-danger)">*</span></label>
          <input class="form-control" id="fb-name" type="text" placeholder="e.g. IT Equipment Request" value="${f.name||''}">
        </div>
        <div class="form-group" style="margin:0">
          <label class="form-label" for="fb-cat">Tab in HCOS Forms</label>
          <select class="form-control" id="fb-cat">
            ${FB_CATEGORIES.map(c=>`<option value="${c.value}" ${f.category===c.value?'selected':''}>${c.label}</option>`).join('')}
          </select>
        </div>
        <div class="form-group" style="margin:0;grid-column:1/-1">
          <label class="form-label" for="fb-desc">Short description (shown on the form card)</label>
          <input class="form-control" id="fb-desc" type="text" placeholder="What is this form for?" value="${f.description||''}">
        </div>
      </div>
    </div></div>

    <!-- ② Routing -->
    <div class="card" style="margin-bottom:14px"><div class="card-body">
      <p class="section-heading">Where submissions are sent</p>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div class="form-group" style="margin:0">
          <label class="form-label" for="fb-route-preset">Office / department</label>
          <select class="form-control" id="fb-route-preset" onchange="fbRoutePresetChange(this)">
            ${FB_ROUTING.map(r=>`<option value="${r.email}" ${(f.routingEmail===r.email||(!FB_ROUTING.find(x=>x.email===f.routingEmail)&&r.email==='custom'))?'selected':''}>${r.label}</option>`).join('')}
          </select>
        </div>
        <div class="form-group" style="margin:0" id="fb-custom-email-wrap">
          <label class="form-label" for="fb-route-email">Custom email address</label>
          <input class="form-control" id="fb-route-email" type="email" placeholder="office@gacl.com.gh" value="${!FB_ROUTING.find(r=>r.email===f.routingEmail&&r.email!=='custom')?(f.routingEmail||''):''}">
        </div>
      </div>
    </div></div>

    <!-- ② b — Downloadable template (for forms that live in other systems) -->
    <div class="card" style="margin-bottom:14px"><div class="card-body">
      <p class="section-heading">Downloadable template <span style="font-weight:400;color:var(--color-text-muted);font-size:12px">(optional)</span></p>
      <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:12px">
        If staff need to download and fill a form from another system (GLICO, pension, bank, etc.), paste its URL or upload it here.
        A <strong>Download Template</strong> button will appear on the form card.
      </p>
      <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end">
        <div class="form-group" style="margin:0">
          <label class="form-label" for="fb-template-url">File URL <span style="font-weight:400;color:var(--color-text-muted)">(paste a link to a PDF/Word/Excel file hosted online or on your server)</span></label>
          <input class="form-control" id="fb-template-url" type="url" placeholder="https://yourdomain.com/forms/glico-accident-form.pdf" value="${f.templateUrl||''}">
        </div>
        <div>
          <label class="form-label" for="fb-template-file">Or upload a file</label>
          <label style="display:inline-flex;align-items:center;gap:8px;cursor:pointer;padding:9px 16px;border:1.5px solid var(--color-border);border-radius:8px;background:#f8fafc;font-size:13px;font-weight:600;color:var(--color-text-secondary)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/></svg>
            Upload file
            <input type="file" id="fb-template-file" accept=".pdf,.doc,.docx,.xls,.xlsx" onchange="fbTemplateFileUpload(this)" style="display:none">
          </label>
        </div>
      </div>
      ${f.templateUrl ? `<div style="margin-top:10px;padding:8px 12px;background:var(--color-success-light);border-radius:6px;font-size:12px;color:var(--color-success);display:flex;align-items:center;gap:8px">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
        Template set: <a href="${f.templateUrl}" target="_blank" rel="noopener" style="color:var(--color-success);font-weight:600">${f.templateFileName||f.templateUrl.split('/').pop()||'View file'}</a>
        <button onclick="fbClearTemplate()" style="border:none;background:transparent;cursor:pointer;color:var(--color-danger);font-size:12px;margin-left:auto">✕ Remove</button>
      </div>` : ''}
    </div></div>

    <!-- ③ Departments -->
    <div class="card" style="margin-bottom:14px"><div class="card-body">
      <p class="section-heading">Who is this form for?</p>
      <p style="font-size:12px;color:var(--color-text-muted);margin-bottom:12px">Select the departments this form is intended for. Leave all unselected to make it available to all staff.</p>
      <div style="display:flex;flex-wrap:wrap;gap:8px" id="fb-dept-tags">
        ${FB_DEPTS.map(d=>`
          <button type="button" class="fb-dept-tag ${(f.departments||[]).includes(d)?'active':''}" onclick="fbToggleDept('${d.replace(/'/g,"\\'")}',this)">
            ${d}
          </button>`).join('')}
      </div>
    </div></div>

    <!-- ④ Sections -->
    <div id="fb-sections-wrap">
      ${(f.sections||[]).map((sec,si) => _renderSectionBlock(sec, si)).join('')}
    </div>

    <button class="btn btn-ghost btn-full" style="margin-bottom:80px;border:2px dashed var(--color-border)" onclick="fbAddSection()">
      <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Add new section
    </button>

    <!-- Sticky save bar -->
    <div style="position:fixed;bottom:0;left:var(--sidebar-width);right:0;background:#fff;border-top:1px solid var(--color-border);padding:14px 24px;display:flex;gap:10px;z-index:100;box-shadow:0 -4px 16px rgba(0,0,0,0.06)">
      <button class="btn btn-primary" onclick="fbSave(false)">
        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
        Save &amp; Publish
      </button>
      <button class="btn btn-ghost" onclick="fbSave(true)">Save as Draft</button>
      <button class="btn btn-ghost" onclick="fbCancelEdit()">Cancel</button>
      <span style="margin-left:auto;font-size:12px;color:var(--color-text-muted);align-self:center" id="fb-summary"></span>
    </div>`;
}

/* ── Section block ── */
function _renderSectionBlock(sec, si) {
  const allFields = _getAllPreviousFields(si); // fields from sections before this one
  const hasCondition = sec.condition && sec.condition.fieldId;

  return `
    <div class="fb-section" id="fbsec-${sec.id}" style="background:#fff;border:1px solid var(--color-border);border-radius:12px;margin-bottom:14px;overflow:hidden">
      <!-- Section header -->
      <div style="background:var(--color-primary);padding:12px 18px;display:flex;align-items:center;gap:10px">
        <div style="display:flex;flex-direction:column;gap:2px">
          <button style="border:none;background:rgba(255,255,255,0.2);color:#fff;border-radius:4px;padding:2px 7px;cursor:pointer;font-size:11px" onclick="fbMoveSection('${sec.id}',-1)" ${si===0?'disabled':''}>▲</button>
          <button style="border:none;background:rgba(255,255,255,0.2);color:#fff;border-radius:4px;padding:2px 7px;cursor:pointer;font-size:11px" onclick="fbMoveSection('${sec.id}',1)" ${si===(_fb.sections.length-1)?'disabled':''}>▼</button>
        </div>
        <span style="color:rgba(255,255,255,0.55);font-size:11px;font-weight:600">SECTION ${si+1}</span>
        <input type="text" value="${sec.title||''}" placeholder="Section title (optional — e.g. 'Personal Details')"
          style="flex:1;background:rgba(255,255,255,0.12);border:1px solid rgba(255,255,255,0.2);border-radius:7px;padding:7px 12px;color:#fff;font-size:13px;font-weight:600;outline:none;font-family:inherit"
          oninput="fbUpdateSectionTitle('${sec.id}',this.value)" onfocus="this.style.background='rgba(255,255,255,0.2)'" onblur="this.style.background='rgba(255,255,255,0.12)'">
        ${si > 0 ? `<button onclick="fbRemoveSection('${sec.id}')" style="border:none;background:rgba(255,100,100,0.25);color:#ffaaaa;padding:5px 10px;border-radius:6px;cursor:pointer;font-size:12px">Remove</button>` : ''}
      </div>

      <!-- Branching condition (only for sections after the first) -->
      ${si > 0 ? `
      <div style="padding:12px 18px;background:${hasCondition?'#fffbeb':'#f8fafc'};border-bottom:1px solid ${hasCondition?'#fde68a':'var(--color-border)'}">
        <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="${hasCondition?'#b45309':'var(--color-text-muted)'}" stroke-width="2"><polyline points="9 18 15 12 9 6"/></svg>
          <span style="font-size:12px;font-weight:600;color:${hasCondition?'#b45309':'var(--color-text-muted)'}">Only show this section when:</span>
          ${allFields.length > 0 ? `
          <select class="form-control" style="width:auto;font-size:12px;height:34px" onchange="fbUpdateCondField('${sec.id}',this.value)">
            <option value="">— always show —</option>
            ${allFields.map(fl=>`<option value="${fl.id}" ${(sec.condition?.fieldId===fl.id)?'selected':''}>${fl.label||'(unlabelled field)'}</option>`).join('')}
          </select>
          ${hasCondition ? `
          <select class="form-control" style="width:auto;font-size:12px;height:34px" onchange="fbUpdateCondOp('${sec.id}',this.value)">
            ${FB_OPERATORS.map(op=>`<option value="${op.value}" ${sec.condition?.operator===op.value?'selected':''}>${op.label}</option>`).join('')}
          </select>
          ${!['filled','empty'].includes(sec.condition?.operator||'') ? `
          ${_condValueInput(sec, allFields)}
          ` : ''}
          ` : ''}
          ` : `<span style="font-size:11px;color:var(--color-text-muted);font-style:italic">Add fields to earlier sections first to enable branching.</span>`}
        </div>
        ${hasCondition ? `<p style="font-size:11px;color:#b45309;margin-top:6px;margin-left:22px">Staff will only see this section if the condition above is met.</p>` : ''}
      </div>
      ` : ''}

      <!-- Fields -->
      <div style="padding:14px 18px" id="fbfields-${sec.id}">
        ${(sec.fields||[]).map((f,fi) => _renderFieldRow(f, fi, sec)).join('')}
        ${(sec.fields||[]).length === 0 ? `<p style="color:var(--color-text-muted);font-size:12px;text-align:center;padding:16px 0">No fields in this section yet.</p>` : ''}
      </div>

      <!-- Add field button -->
      <div style="padding:10px 18px 16px;border-top:1px solid #f1f5f9;display:flex;gap:8px;flex-wrap:wrap">
        ${FB_FIELD_TYPES.map(t=>`
          <button class="fb-add-field-btn" onclick="fbAddField('${sec.id}','${t.value}')" title="${t.label}" style="font-size:11px;padding:5px 10px;border-radius:6px;border:1px solid var(--color-border);background:#f8fafc;cursor:pointer;color:var(--color-text-secondary)">
            ${t.icon} ${t.label}
          </button>`).join('')}
      </div>
    </div>`;
}

function _condValueInput(sec, allFields) {
  const refField = allFields.find(f => f.id === sec.condition?.fieldId);
  const val = sec.condition?.value || '';
  // If the referenced field is a select/radio, show a dropdown of its options
  if (refField && (refField.type === 'select' || refField.type === 'radio') && (refField.options||[]).length) {
    return `<select class="form-control" style="width:auto;font-size:12px;height:34px" onchange="fbUpdateCondValue('${sec.id}',this.value)">
      <option value="">— choose —</option>
      ${refField.options.map(o=>`<option value="${o}" ${val===o?'selected':''}>${o}</option>`).join('')}
    </select>`;
  }
  return `<input class="form-control" style="width:160px;font-size:12px;height:34px" type="text" placeholder="value to match" value="${val}" oninput="fbUpdateCondValue('${sec.id}',this.value)">`;
}

/* ── Field row ── */
function _renderFieldRow(field, fi, sec) {
  const isChoice = ['select','radio','checkbox_multi'].includes(field.type);
  const isCheck  = field.type === 'checkbox';
  const isFile   = field.type === 'file_info';
  const typeLabel = FB_FIELD_TYPES.find(t=>t.value===field.type)?.label || field.type;
  const lastIdx   = (sec.fields||[]).length - 1;

  return `
    <div class="fb-field-row" id="fbfield-${field.id}" style="border:1px solid #e8edf5;border-radius:8px;padding:12px 14px;margin-bottom:8px;background:#fafbfd">
      <div style="display:flex;align-items:flex-start;gap:8px">
        <!-- Move -->
        <div style="display:flex;flex-direction:column;gap:2px;padding-top:2px">
          <button style="border:none;background:transparent;cursor:pointer;color:var(--color-text-muted);font-size:11px;padding:1px 4px" onclick="fbMoveField('${sec.id}','${field.id}',-1)" ${fi===0?'disabled':''}>▲</button>
          <button style="border:none;background:transparent;cursor:pointer;color:var(--color-text-muted);font-size:11px;padding:1px 4px" onclick="fbMoveField('${sec.id}','${field.id}',1)" ${fi===lastIdx?'disabled':''}>▼</button>
        </div>
        <!-- Content -->
        <div style="flex:1">
          <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin-bottom:${isChoice||isFile?'10px':'0'}">
            <!-- Field type badge -->
            <span style="font-size:10px;font-weight:700;background:var(--color-primary);color:#fff;padding:2px 8px;border-radius:4px">${typeLabel}</span>
            <!-- Label -->
            <input class="form-control" style="flex:1;min-width:180px;height:34px" type="text" placeholder="Question / label *" value="${field.label||''}" oninput="fbFieldProp('${sec.id}','${field.id}','label',this.value)">
            <!-- Required -->
            ${!isFile ? `<label style="display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:var(--color-text-secondary);cursor:pointer;white-space:nowrap">
              <input type="checkbox" ${field.required?'checked':''} onchange="fbFieldProp('${sec.id}','${field.id}','required',this.checked)" style="accent-color:var(--color-primary);width:13px;height:13px"> Required
            </label>` : ''}
            <!-- Delete -->
            <button onclick="fbRemoveField('${sec.id}','${field.id}')" style="border:none;background:transparent;cursor:pointer;color:var(--color-danger);font-size:18px;padding:2px 6px" title="Remove this field">×</button>
          </div>
          <!-- Placeholder (not for choice / check / file types) -->
          ${(!isChoice && !isCheck && !isFile) ? `
          <input class="form-control" style="font-size:12px;margin-top:8px" type="text" placeholder="Helper text / placeholder (optional)" value="${field.placeholder||''}" oninput="fbFieldProp('${sec.id}','${field.id}','placeholder',this.value)">
          ` : ''}
          <!-- Options (for select/radio/checkbox_multi) -->
          ${isChoice ? `
          <div style="margin-top:8px">
            <label style="font-size:11px;font-weight:700;color:var(--color-text-muted);text-transform:uppercase;letter-spacing:.5px">
              Options — one per line
            </label>
            <textarea class="form-control" style="height:80px;margin-top:5px;font-size:12px;padding:8px 10px;resize:vertical" placeholder="Option A&#10;Option B&#10;Option C" oninput="fbFieldOptions('${sec.id}','${field.id}',this.value)">${(field.options||[]).join('\n')}</textarea>
          </div>` : ''}
          <!-- File info text -->
          ${isFile ? `<p style="font-size:11px;color:var(--color-text-muted);margin-top:6px;background:#f1f5f9;padding:8px 10px;border-radius:6px">
            💡 This adds a note telling staff to attach supporting documents. Staff attach files by emailing them alongside the submission.
          </p>` : ''}
        </div>
      </div>
    </div>`;
}

/* ── Helper: get all fields from sections before index si ── */
function _getAllPreviousFields(si) {
  const fields = [];
  (_fb.sections||[]).slice(0,si).forEach(sec => {
    (sec.fields||[]).forEach(f => { if (f.label) fields.push({ id:f.id, label:f.label, type:f.type, options:f.options||[] }); });
  });
  return fields;
}

/* ── Bind live summary ── */
function _bindEditorLive() {
  const fbRoutePreset = document.getElementById('fb-route-preset');
  const customWrap    = document.getElementById('fb-custom-email-wrap');
  if (fbRoutePreset && customWrap) {
    const isCustom = fbRoutePreset.value === 'custom' || !FB_ROUTING.find(r=>r.email===fbRoutePreset.value&&r.email!=='custom');
    customWrap.style.display = (fbRoutePreset.value==='custom'||isCustom) ? 'block' : 'none';
  }
  _updateSummary();
}

function _updateSummary() {
  const el = document.getElementById('fb-summary');
  if (!el || !_fb) return;
  const secs   = _fb.sections.length;
  const fields = _fb.sections.reduce((t,s)=>t+(s.fields||[]).length, 0);
  const conds  = _fb.sections.filter(s=>s.condition?.fieldId).length;
  el.textContent = `${secs} section${secs!==1?'s':''} · ${fields} field${fields!==1?'s':''} · ${conds} branch${conds!==1?'es':''}`;
}

/* ════════════════════════════════════════════════════════════
   EDITOR ACTIONS
   ════════════════════════════════════════════════════════════ */

function fbNewForm() {
  _fb = {
    id:            'form_' + Date.now(),
    name:          '',
    description:   '',
    category:      'admin',
    departments:   [],
    routingOffice: FB_ROUTING[0].label,
    routingEmail:  FB_ROUTING[0].email,
    enabled:       false,
    createdBy:     (typeof M365_USER!=='undefined'&&M365_USER) ? M365_USER.name : 'Admin',
    createdAt:     new Date().toISOString().slice(0,10),
    sections:      [{ id: _uid(), title:'', fields:[], condition:null }],
  };
  renderFormBuilderPage();
}

function fbEditForm(id) {
  const form = fbGetForms().find(f=>f.id===id);
  if (!form) return;
  // Migrate old forms that used flat 'fields' instead of 'sections'
  if (!form.sections && form.fields) {
    form.sections = [{ id:_uid(), title:'', fields:form.fields, condition:null }];
  }
  _fb = JSON.parse(JSON.stringify(form));
  renderFormBuilderPage();
}

function fbCancelEdit() { _fb = null; renderFormBuilderPage(); }

function fbDeleteForm(id) {
  if (!confirm('Permanently delete this form? Staff will no longer be able to submit it.')) return;
  fbSaveForms(fbGetForms().filter(f=>f.id!==id));
  renderFormBuilderPage();
  toast('Form deleted');
}

function fbToggleEnabled(id) {
  const forms = fbGetForms();
  const f = forms.find(x=>x.id===id);
  if (!f) return;
  f.enabled = !f.enabled;
  fbSaveForms(forms);
  renderFormBuilderPage();
  toast(f.enabled ? 'Form published — visible to staff ✓' : 'Form hidden from staff (draft)');
}

/* ── Section actions ── */
function fbAddSection() {
  if (!_fb) return;
  _fb.sections.push({ id:_uid(), title:'', fields:[], condition:null });
  renderFormBuilderPage();
}

function fbRemoveSection(id) {
  if (!_fb) return;
  if (!confirm('Remove this section and all its fields?')) return;
  _fb.sections = _fb.sections.filter(s=>s.id!==id);
  renderFormBuilderPage();
}

function fbMoveSection(id, dir) {
  if (!_fb) return;
  const idx = _fb.sections.findIndex(s=>s.id===id);
  const newIdx = idx + dir;
  if (newIdx < 0 || newIdx >= _fb.sections.length) return;
  [_fb.sections[idx],_fb.sections[newIdx]] = [_fb.sections[newIdx],_fb.sections[idx]];
  renderFormBuilderPage();
}

function fbUpdateSectionTitle(secId, val) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (sec) { sec.title = val; _updateSummary(); }
}

/* ── Condition actions ── */
function fbUpdateCondField(secId, fieldId) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (!sec) return;
  if (!fieldId) { sec.condition = null; }
  else { sec.condition = sec.condition||{}; sec.condition.fieldId = fieldId; sec.condition.operator = sec.condition.operator||'eq'; sec.condition.value = sec.condition.value||''; }
  renderFormBuilderPage();
}

function fbUpdateCondOp(secId, op) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (sec?.condition) { sec.condition.operator = op; renderFormBuilderPage(); }
}

function fbUpdateCondValue(secId, val) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (sec?.condition) { sec.condition.value = val; }
}

/* ── Department tag toggle ── */
function fbToggleDept(dept, btn) {
  if (!_fb) return;
  const depts = _fb.departments || [];
  const idx   = depts.indexOf(dept);
  if (idx >= 0) { depts.splice(idx,1); btn.classList.remove('active'); }
  else           { depts.push(dept);   btn.classList.add('active'); }
  _fb.departments = depts;
}

/* ── Routing preset ── */
function fbRoutePresetChange(sel) {
  const wrap = document.getElementById('fb-custom-email-wrap');
  if (wrap) wrap.style.display = sel.value==='custom' ? 'block' : 'none';
}

/* ── Field actions ── */
function fbAddField(secId, type) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (!sec) return;
  sec.fields.push({ id:_uid(), type:type||'text', label:'', placeholder:'', required:false, options:[] });
  renderFormBuilderPage();
  // Scroll to the new field
  setTimeout(() => {
    const wrap = document.getElementById('fbfields-'+secId);
    if (wrap) { const last = wrap.querySelector('.fb-field-row:last-child'); if(last) last.scrollIntoView({behavior:'smooth',block:'nearest'}); }
  }, 100);
}

function fbRemoveField(secId, fieldId) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (!sec) return;
  sec.fields = sec.fields.filter(f=>f.id!==fieldId);
  // Clear any conditions referencing this field
  _fb.sections.forEach(s => { if (s.condition?.fieldId===fieldId) s.condition=null; });
  renderFormBuilderPage();
}

function fbMoveField(secId, fieldId, dir) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  if (!sec) return;
  const idx = sec.fields.findIndex(f=>f.id===fieldId);
  const ni  = idx + dir;
  if (ni<0||ni>=sec.fields.length) return;
  [sec.fields[idx],sec.fields[ni]] = [sec.fields[ni],sec.fields[idx]];
  renderFormBuilderPage();
}

function fbFieldProp(secId, fieldId, prop, val) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  const f   = sec?.fields.find(f=>f.id===fieldId);
  if (f) { f[prop] = val; if(prop==='label') _updateSummary(); }
}

function fbFieldOptions(secId, fieldId, raw) {
  const sec = _fb?.sections.find(s=>s.id===secId);
  const f   = sec?.fields.find(f=>f.id===fieldId);
  if (f) f.options = raw.split('\n').map(s=>s.trim()).filter(Boolean);
}

/* ── Template upload / clear ── */
/**
 * Converts an uploaded template file to a data URL and stores it on the draft form.
 * @param {HTMLInputElement} input
 */
function fbTemplateFileUpload(input) {
  const file = input.files && input.files[0];
  if (!file) return;
  if (file.size > 5 * 1024 * 1024) { toast('File too large — max 5 MB. Host larger files online and paste the URL.', 'err'); input.value=''; return; }
  const reader = new FileReader();
  reader.onload = function(e) {
    if (_fb) {
      _fb.templateUrl      = e.target.result;   // data: URL
      _fb.templateFileName = file.name;
      renderFormBuilderPage();
      toast('Template uploaded: ' + file.name);
    }
  };
  reader.readAsDataURL(file);
}

/** @returns {void} */
function fbClearTemplate() {
  if (_fb) { _fb.templateUrl = ''; _fb.templateFileName = ''; renderFormBuilderPage(); }
}

/* ── Save ── */
function fbSave(asDraft) {
  if (!_fb) return;
  const name = document.getElementById('fb-name')?.value.trim();
  if (!name) { toast('Please enter a form name','err'); document.getElementById('fb-name')?.focus(); return; }

  const totalFields = _fb.sections.reduce((t,s)=>t+(s.fields||[]).length, 0);
  if (totalFields === 0) { toast('Please add at least one field','err'); return; }
  const unlabelled = _fb.sections.some(s=>(s.fields||[]).some(f=>!f.label.trim()));
  if (unlabelled) { toast('All fields need a label','err'); return; }

  const routePreset = document.getElementById('fb-route-preset')?.value;
  const routeEmail  = routePreset==='custom'
    ? (document.getElementById('fb-route-email')?.value.trim()||'')
    : routePreset;
  const routeOffice = FB_ROUTING.find(r=>r.email===routePreset)?.label || routeEmail;

  const templateUrlInput = document.getElementById('fb-template-url')?.value.trim();
  Object.assign(_fb, {
    name:             name,
    description:      document.getElementById('fb-desc')?.value.trim()||'',
    category:         document.getElementById('fb-cat')?.value||'admin',
    routingEmail:     routeEmail,
    routingOffice:    routeOffice,
    enabled:          !asDraft,
    // template: use the URL input field if it has a value, otherwise keep what was set by file upload
    templateUrl:      templateUrlInput || _fb.templateUrl || '',
    templateFileName: templateUrlInput ? (templateUrlInput.split('/').pop()||'Template') : (_fb.templateFileName||''),
  });

  fbSaveForm(_fb);
  _fb = null;
  renderFormBuilderPage();
  toast(asDraft ? 'Saved as draft' : 'Form published — visible to staff ✓');
}

/* ════════════════════════════════════════════════════════════
   RUNTIME — render form for staff + conditional logic
   ════════════════════════════════════════════════════════════ */

/**
 * Renders a complete custom form (with branching) into a container element.
 * @param {any} form
 * @param {HTMLElement} container
 */
function fbRenderFormInPanel(form, container) {
  const fid = 'fbf-' + form.id;
  container.innerHTML = `
    <div style="border:1px solid var(--color-border);border-radius:var(--radius-lg);overflow:hidden;margin-bottom:14px">
      <div style="padding:14px 18px;border-bottom:1px solid var(--color-border);display:flex;align-items:center;justify-content:space-between;background:var(--color-surface-2)">
        <div style="flex:1">
          <div style="font-size:15px;font-weight:700;color:var(--color-primary)">${form.name}</div>
          ${form.description?`<div style="font-size:12px;color:var(--color-text-muted);margin-top:2px">${form.description}</div>`:''}
          <div style="font-size:11px;color:var(--color-text-muted);margin-top:3px">Routes to: ${form.routingOffice}</div>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-shrink:0">
          ${form.templateUrl ? `<a href="${form.templateUrl}" download="${form.templateFileName||'template'}" target="_blank" class="btn btn-ghost btn-sm" style="height:34px" title="Download the form template">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Download Template
          </a>` : ''}
          <button class="btn btn-ghost btn-sm" id="${fid}-toggle" onclick="fbTogglePanel('${fid}')" style="height:34px">▼ Fill &amp; Submit</button>
        </div>
      </div>
      <div id="${fid}-body" style="display:none">
        <form id="${fid}-form" onsubmit="fbSubmit(event,'${form.id}')" novalidate>
          <div id="${fid}-sections">
            ${(form.sections||[]).map((sec,si) => `
              <div class="fb-runtime-section" id="${fid}-sec-${sec.id}" style="padding:18px 20px;border-bottom:1px solid #f1f5f9">
                ${sec.title?`<h4 style="font-size:13px;font-weight:700;color:var(--color-primary);margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid var(--color-accent)">${sec.title}</h4>`:''}
                ${(sec.fields||[]).map(f => fbRenderRuntimeField(f)).join('')}
              </div>`).join('')}
          </div>
          <!-- Optional file upload for custom forms -->
          <div style="padding:0 20px 4px">
            <div style="padding:12px 14px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:8px">
              <label style="font-size:12px;font-weight:600;color:var(--color-text-secondary);margin-bottom:6px;display:flex;align-items:center;gap:8px">
                <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21.44 11.05l-9.19 9.19a6 6 0 0 1-8.49-8.49l9.19-9.19a4 4 0 0 1 5.66 5.66l-9.2 9.19a2 2 0 0 1-2.83-2.83l8.49-8.48"/></svg>
                Attach supporting documents
                <span style="font-weight:400;color:var(--color-text-muted)">(optional — PDF, Word, Excel, images)</span>
              </label>
              <input type="file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.jpg,.jpeg,.png,.gif" name="__attachments" style="font-size:12px;width:100%">
            </div>
          </div>
          <div style="padding:14px 20px">
            <button type="submit" class="btn btn-primary">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
              Submit Form
            </button>
          </div>
        </form>
      </div>
    </div>`;

  // Attach live branching listeners
  const formEl = document.getElementById(fid+'-form');
  if (formEl) {
    formEl.addEventListener('change', () => fbEvalConditions(form, fid));
    formEl.addEventListener('input',  () => fbEvalConditions(form, fid));
    fbEvalConditions(form, fid); // initial evaluation
  }
}

/** @param {{id:string,type:string,label:string,placeholder:string,required:boolean,options:string[]}} field */
function fbRenderRuntimeField(field) {
  const ph  = field.placeholder ? `placeholder="${field.placeholder}"` : '';
  const req = field.required;
  const lbl = `<label class="form-label" for="fbri-${field.id}">${field.label}${req?'&nbsp;<span style="color:var(--color-danger)">*</span>':''}</label>`;
  const mb  = 'style="margin-bottom:14px"';

  if (field.type==='select') return `<div class="form-group" ${mb}>${lbl}<select class="form-control" id="fbri-${field.id}" name="${field.id}" ${req?'required':''}><option value="" disabled selected>— Select —</option>${(field.options||[]).map(o=>`<option value="${o}">${o}</option>`).join('')}</select></div>`;
  if (field.type==='radio') return `<div class="form-group" ${mb}><p class="form-label">${field.label}${req?'&nbsp;<span style="color:var(--color-danger)">*</span>':''}</p>${(field.options||[]).map(o=>`<label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:6px;cursor:pointer"><input type="radio" name="${field.id}" value="${o}" ${req?'required':''}> ${o}</label>`).join('')}</div>`;
  if (field.type==='checkbox_multi') return `<div class="form-group" ${mb}><p class="form-label">${field.label}</p>${(field.options||[]).map(o=>`<label style="display:flex;align-items:center;gap:8px;font-size:13px;margin-bottom:6px;cursor:pointer"><input type="checkbox" name="${field.id}" value="${o}"> ${o}</label>`).join('')}</div>`;
  if (field.type==='checkbox') return `<div class="form-group" ${mb} style="display:flex;align-items:center;gap:10px"><input type="checkbox" id="fbri-${field.id}" name="${field.id}" style="width:18px;height:18px;accent-color:var(--color-primary)"><label for="fbri-${field.id}" style="font-size:13px;cursor:pointer">${field.label}</label></div>`;
  if (field.type==='textarea') return `<div class="form-group" ${mb}>${lbl}<textarea class="form-control" id="fbri-${field.id}" name="${field.id}" rows="3" style="height:auto;padding:10px 14px;resize:vertical" ${ph} ${req?'required':''}></textarea></div>`;
  if (field.type==='file_info') return `<div ${mb} style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;font-size:12px;color:var(--color-text-secondary)">📎 <strong>${field.label}</strong> — Please attach any supporting documents when emailing your submission.</div>`;
  return `<div class="form-group" ${mb}>${lbl}<input class="form-control" type="${field.type}" id="fbri-${field.id}" name="${field.id}" ${ph} ${req?'required':''}></div>`;
}

/**
 * Evaluates all branching conditions and shows/hides sections accordingly.
 * @param {any} form
 * @param {string} fid  form panel prefix
 */
function fbEvalConditions(form, fid) {
  const formEl = document.getElementById(fid+'-form');
  if (!formEl) return;

  // Build a map of fieldId → current value(s)
  const values = {};
  (form.sections||[]).forEach(sec => {
    (sec.fields||[]).forEach(f => {
      const inputs = formEl.querySelectorAll('[name="'+f.id+'"]');
      if (inputs.length === 1) {
        const inp = inputs[0];
        values[f.id] = inp.type==='checkbox' ? (inp.checked?'yes':'') : inp.value;
      } else if (inputs.length > 1) {
        // radio or checkbox_multi
        const checked = Array.from(inputs).filter(i=>i.checked).map(i=>i.value);
        values[f.id] = checked.join(', ');
      }
    });
  });

  // Evaluate each section's condition
  (form.sections||[]).forEach((sec, si) => {
    const el = document.getElementById(fid+'-sec-'+sec.id);
    if (!el) return;
    if (si === 0 || !sec.condition?.fieldId) { el.style.display=''; return; }

    const { fieldId, operator, value } = sec.condition;
    const actual = (values[fieldId]||'').toLowerCase().trim();
    const expected = (value||'').toLowerCase().trim();

    let show = false;
    switch (operator) {
      case 'eq':           show = actual === expected; break;
      case 'neq':          show = actual !== expected; break;
      case 'contains':     show = actual.includes(expected); break;
      case 'not_contains': show = !actual.includes(expected); break;
      case 'filled':       show = actual.length > 0; break;
      case 'empty':        show = actual.length === 0; break;
      default:             show = true;
    }
    el.style.display = show ? '' : 'none';
  });
}

function fbTogglePanel(fid) {
  const body = document.getElementById(fid+'-body');
  const btn  = document.getElementById(fid+'-toggle');
  if (!body) return;
  const open = body.style.display !== 'none';
  body.style.display = open ? 'none' : 'block';
  if (btn) btn.textContent = open ? '▼ Fill & Submit Form' : '▲ Close Form';
}

function fbSubmit(e, formId) {
  e.preventDefault();
  const form = fbGetForms().find(f=>f.id===formId);
  if (!form) return;

  // Validate required fields in visible sections
  const fid     = 'fbf-' + formId;
  const formEl  = document.getElementById(fid+'-form');
  const errors  = [];
  (form.sections||[]).forEach(sec => {
    const secEl = document.getElementById(fid+'-sec-'+sec.id);
    if (!secEl || secEl.style.display==='none') return; // hidden by condition
    (sec.fields||[]).forEach(f => {
      if (!f.required) return;
      const inputs = formEl.querySelectorAll('[name="'+f.id+'"]');
      let filled = false;
      inputs.forEach(i => { if(i.type==='checkbox'?i.checked:i.value) filled=true; });
      if (!filled) errors.push(f.label||'A required field');
    });
  });

  if (errors.length) {
    toast('Please fill in: ' + errors.slice(0,2).join(', ') + (errors.length>2?'…':''), 'err');
    return;
  }

  // Collect attached files for routing message
  const fileInput = formEl.querySelector('input[type="file"]');
  const fileNames = fileInput && fileInput.files ? Array.from(fileInput.files).map(f=>f.name) : [];
  const attachNote = fileNames.length ? ` Attached files: ${fileNames.join(', ')}.` : '';
  const msg = `Submitted to ${form.routingOffice}. You will receive a reference number shortly.${attachNote}`;

  if (typeof hrSubmit === 'function') {
    hrSubmit({ preventDefault:()=>{}, target: formEl }, form.name, msg);
  } else { toast('Form submitted — thank you!'); formEl.reset(); }
}

/* ════════════════════════════════════════════════════════════
   INJECT CUSTOM FORMS INTO HR FORMS PAGE
   ════════════════════════════════════════════════════════════ */
function injectCustomForms() {
  // Remove any previously injected custom forms
  document.querySelectorAll('[data-custom-form]').forEach(el=>el.remove());

  const catMap = { personal:'hrf-personal', claims:'hrf-claims', safety:'hrf-safety', admin:'hrf-admin', other:'hrf-personal' };
  fbGetForms().filter(f=>f.enabled).forEach(form => {
    // Migrate old flat-field forms
    if (!form.sections && form.fields) form.sections = [{ id:'s0', title:'', fields:form.fields, condition:null }];
    const panelId = catMap[form.category]||'hrf-admin';
    const panel   = document.getElementById(panelId);
    if (!panel) return;
    const wrapper = document.createElement('div');
    wrapper.setAttribute('data-custom-form', form.id);
    panel.appendChild(wrapper);
    fbRenderFormInPanel(form, wrapper);
  });
}
