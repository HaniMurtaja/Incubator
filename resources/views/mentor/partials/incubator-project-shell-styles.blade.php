<style>
/* ══════════════════════════════════════════
   MENTOR PROJECT SHOW — Styles
   Variables inherited from app.css
   ══════════════════════════════════════════ */

/* ── Stage strip wrapper — light card ── */
.inc-stage-wrap {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius);
    padding: 1rem 1.25rem 1.25rem;
    margin-bottom: 1.25rem;
}
.inc-strip-header {
    display: flex; align-items: center; justify-content: space-between;
    margin-bottom: 1rem;
}
.inc-strip-label {
    font-size: 11px; color: var(--txt2);
    text-transform: uppercase; letter-spacing: .7px; font-weight: 700;
}
.inc-strip-legend { display: flex; align-items: center; gap: 16px; }
.inc-leg { display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 600; }
.inc-leg-dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
.inc-leg.completed   { color: #0B7B5C; }
.inc-leg.in_progress { color: #1A56DB; }
.inc-leg.not_started { color: #C0392B; }
.inc-leg.completed   .inc-leg-dot { background: #1D9E75; }
.inc-leg.in_progress .inc-leg-dot { background: #1A56DB; }
.inc-leg.not_started .inc-leg-dot { background: #E24B4A; }

/* Stage tabs — full-width, bigger, tab-bar style */
.inc-stages {
    display: grid;
    grid-template-columns: repeat(9, 1fr);
    gap: 6px;
}

.stage-tab {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    padding: 14px 6px 12px;
    border-radius: 10px;
    font-size: 11.5px;
    text-decoration: none;
    cursor: pointer;
    transition: transform .15s, box-shadow .15s;
    position: relative;
    text-align: center;
    min-height: 80px;
}
.stage-tab:hover { transform: translateY(-2px); text-decoration: none; }
.stage-tab .tab-num {
    display: block; font-size: 10px; font-weight: 700;
    letter-spacing: .4px; margin-bottom: 6px; opacity: .75;
}
.stage-tab .tab-name {
    display: block; line-height: 1.35; font-weight: 600;
    font-size: 11.5px; word-break: break-word;
}
.stage-tab .tab-dot {
    display: block; width: 7px; height: 7px; border-radius: 50%; margin: 8px auto 0;
}

/* Status colour themes — on a light background */
.stage-tab.completed {
    background: #E6F5F0;
    color: #064E3B;
    border: 1.5px solid #A7D9C9;
}
.stage-tab.completed .tab-num { color: #0B7B5C; }
.stage-tab.completed .tab-dot { background: #1D9E75; }

.stage-tab.in_progress {
    background: #EBF2FF;
    color: #0C347A;
    border: 1.5px solid #BFCFEF;
}
.stage-tab.in_progress .tab-num { color: #1A56DB; }
.stage-tab.in_progress .tab-dot { background: #1A56DB; }

.stage-tab.not_started {
    background: #FDECEA;
    color: #7B1D1D;
    border: 1.5px solid #F4B8B2;
}
.stage-tab.not_started .tab-num { color: #C0392B; }
.stage-tab.not_started .tab-dot { background: #E24B4A; }

/* Active/current tab — stronger ring + bottom arrow */
.stage-tab.active-stage {
    box-shadow: 0 0 0 2.5px #0F1724, 0 4px 12px rgba(15,23,36,.12);
    transform: translateY(-2px);
}
.stage-tab.active-stage::after {
    content: '';
    position: absolute; bottom: -14px; left: 50%; transform: translateX(-50%);
    width: 0; height: 0;
    border-left: 6px solid transparent; border-right: 6px solid transparent;
}
.stage-tab.completed.active-stage::after   { border-top: 6px solid #A7D9C9; }
.stage-tab.in_progress.active-stage::after { border-top: 6px solid #BFCFEF; }
.stage-tab.not_started.active-stage::after { border-top: 6px solid #F4B8B2; }

/* ── Two-column layout ── */
.inc-cols {
    display: grid; grid-template-columns: 1fr 292px; gap: 1.125rem; align-items: start;
}
@media (max-width: 992px) { .inc-cols { grid-template-columns: 1fr; } }

/* ── Section cards ── */
.inc-card { border-radius: var(--radius); overflow: hidden; margin-bottom: 1rem; }
.inc-card:last-child { margin-bottom: 0; }

.inc-card-white  { background: var(--surface); border: 1px solid var(--border); }
.inc-card-green  { background: var(--green-light-bg); border: 1px solid var(--green-border); }
.inc-card-slate  { background: var(--navy); border: 1px solid rgba(255,255,255,.07); }
.inc-card-light  { background: var(--surface2); border: 1px solid var(--border); }

.inc-card-head {
    padding: .875rem 1.125rem;
    display: flex; align-items: flex-start; justify-content: space-between;
}
.inc-card-white .inc-card-head { border-bottom: 1px solid var(--border); }
.inc-card-green .inc-card-head { border-bottom: 1px solid var(--green-border); }
.inc-card-slate .inc-card-head { border-bottom: 1px solid rgba(255,255,255,.08); }
.inc-card-light .inc-card-head { border-bottom: 1px solid var(--border); }

.inc-card-title { font-size: 13px; font-weight: 600; margin: 0; }
.inc-card-white .inc-card-title,
.inc-card-light .inc-card-title  { color: var(--txt); }
.inc-card-green .inc-card-title  { color: #064E3B; }
.inc-card-slate .inc-card-title  { color: #EEF3F7; }

.inc-card-desc { font-size: 11.5px; margin-top: 3px; margin-bottom: 0; }
.inc-card-white .inc-card-desc,
.inc-card-light .inc-card-desc  { color: var(--txt3); }
.inc-card-green .inc-card-desc  { color: var(--green); }
.inc-card-slate .inc-card-desc  { color: #6B7A99; }
.inc-card-body { padding: 1.125rem; }

/* ── Project info bar ── */
.inc-proj-info {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 1rem 1.25rem; margin-bottom: 1.25rem;
    display: flex; align-items: center; gap: 2rem; flex-wrap: wrap;
}
.inc-info-field { font-size: 12.5px; color: var(--txt2); }
.inc-info-field strong { color: var(--txt); font-weight: 600; }

/* ── Add task form ── */
.inc-add-grid {
    display: grid; grid-template-columns: 1fr 1fr auto;
    gap: 8px; align-items: end; margin-bottom: 8px;
}
@media (max-width: 768px) { .inc-add-grid { grid-template-columns: 1fr 1fr; } }
.inc-add-subgrid { display: grid; grid-template-columns: auto 1fr; gap: 8px; margin-bottom: 10px; }
.inc-inp {
    font-size: 12px; padding: 7px 10px; border-radius: var(--radius-sm);
    border: 1px solid var(--green-border); background: #fff;
    color: var(--txt); width: 100%; outline: none;
    font-family: 'Cairo', sans-serif;
}
.inc-inp:focus { border-color: var(--green); }
.inc-date {
    font-size: 12px; padding: 6px 9px; border-radius: var(--radius-sm);
    border: 1px solid var(--green-border); background: #fff;
    color: var(--txt); outline: none; font-family: 'Cairo', sans-serif;
}
.inc-date:focus { border-color: var(--green); }
.inc-textarea {
    width: 100%; border: 1px solid var(--green-border); border-radius: var(--radius-sm);
    padding: 8px 10px; font-size: 12px; color: var(--txt); background: #fff;
    resize: none; outline: none; font-family: 'Cairo', sans-serif;
}
.inc-textarea:focus { border-color: var(--green); }
.inc-btn-add {
    padding: 7px 16px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 700;
    background: var(--green); color: #fff; border: none; cursor: pointer; white-space: nowrap;
    font-family: 'Cairo', sans-serif;
}
.inc-btn-add:hover { background: var(--green-dark); }
.inc-field-lbl {
    font-size: 10px; text-transform: uppercase; letter-spacing: .5px; font-weight: 700;
    margin-bottom: 5px; color: var(--green);
}

/* ── Task cards ── */
.inc-task-cards { display: flex; flex-direction: column; gap: 10px; }

.inc-task-card {
    background: var(--surface2); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 13px 14px 13px 16px;
    position: relative; overflow: hidden;
}
.inc-task-card::before {
    content: ''; position: absolute; left: 0; top: 0; bottom: 0; width: 3px;
}
.inc-task-card.task-not_started::before    { background: var(--ph-ns-text); }
.inc-task-card.task-in_progress::before    { background: var(--ph-ip-text); }
.inc-task-card.task-submitted::before      { background: #F59E0B; }
.inc-task-card.task-approved::before       { background: var(--ph-done-text); }
.inc-task-card.task-changes_requested::before { background: var(--ph-ns-text); }

.inc-task-top {
    display: grid; grid-template-columns: 32px 1fr auto;
    gap: 11px; align-items: start; margin-bottom: 10px;
}
.inc-task-num {
    width: 32px; height: 32px; border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 700; flex-shrink: 0;
}
.inc-task-num.num-not_started    { background: #F0F2F5; color: #6B7A99; }
.inc-task-num.num-in_progress    { background: var(--accent-bg); color: var(--accent); }
.inc-task-num.num-submitted      { background: var(--amber-bg); color: var(--amber); }
.inc-task-num.num-approved       { background: var(--green-bg); color: var(--green); }
.inc-task-num.num-changes_requested { background: var(--red-bg); color: var(--red); }

.inc-task-name { font-size: 13px; font-weight: 600; color: var(--txt); margin-bottom: 3px; }
.inc-task-desc { font-size: 12px; color: var(--txt2); line-height: 1.45; }
.inc-task-due  { font-size: 10.5px; color: var(--txt3); margin-top: 3px; }

/* ── Status pills ── */
.inc-pill { padding: 3px 9px; border-radius: 20px; font-size: 10.5px; font-weight: 600; white-space: nowrap; display: inline-block; }
.inc-pill.pill-not_started    { background: var(--red-bg);   color: var(--red);    border: 1px solid var(--red-border); }
.inc-pill.pill-in_progress    { background: var(--accent-bg); color: var(--accent); border: 1px solid var(--accent-border); }
.inc-pill.pill-submitted      { background: var(--amber-bg); color: var(--amber);  border: 1px solid var(--amber-border); }
.inc-pill.pill-approved       { background: var(--green-bg); color: var(--green);  border: 1px solid var(--green-border); }
.inc-pill.pill-changes_requested { background: var(--red-bg); color: var(--red);   border: 1px solid var(--red-border); }
.inc-pill.pill-completed      { background: var(--green-bg); color: var(--green);  border: 1px solid var(--green-border); }
.inc-pill.pill-pending   { background: var(--amber-bg);  color: var(--amber);  border: 1px solid var(--amber-border); }
.inc-pill.pill-accepted  { background: var(--green-bg);   color: var(--green);  border: 1px solid var(--green-border); }
.inc-pill.pill-rejected  { background: var(--red-bg);    color: var(--red);    border: 1px solid var(--red-border); }

.inc-pill.pill-in_progress_stage { background: var(--accent-bg); color: var(--accent); border: 1px solid var(--accent-border); }

/* ── Action panels inside task card ── */
.inc-panel {
    background: var(--surface); border: 1px solid var(--border);
    border-radius: 8px; padding: 9px 11px; margin-top: 8px;
}
.inc-panel-label {
    font-size: 10px; color: var(--txt3); text-transform: uppercase;
    letter-spacing: .5px; font-weight: 700; margin-bottom: 7px;
}
.inc-panel-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
.inc-select {
    font-size: 12px; padding: 5px 8px; border-radius: var(--radius-sm);
    border: 1px solid var(--border2); background: var(--surface2);
    color: var(--txt); flex: 1; min-width: 110px; outline: none; cursor: pointer;
    font-family: 'Cairo', sans-serif;
}
.inc-select:focus { border-color: var(--accent); }
.inc-input-sm {
    font-size: 12px; padding: 5px 9px; border-radius: var(--radius-sm);
    border: 1px solid var(--border2); background: var(--surface2);
    color: var(--txt); flex: 1; outline: none; font-family: 'Cairo', sans-serif;
}
.inc-input-sm:focus { border-color: var(--accent); background: var(--surface); }
.inc-btn-dark {
    padding: 5px 13px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 700;
    background: var(--navy); color: #fff; border: none; cursor: pointer; white-space: nowrap;
    font-family: 'Cairo', sans-serif;
}
.inc-btn-dark:hover { background: #1E293B; }
.inc-btn-green {
    padding: 5px 13px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 700;
    background: var(--green); color: #fff; border: none; cursor: pointer; white-space: nowrap;
    font-family: 'Cairo', sans-serif;
}
.inc-btn-green:hover { background: var(--green-dark); }

/* ── Submission review block ── */
.inc-submission {
    background: var(--amber-light); border: 1px solid var(--amber-border);
    border-radius: 8px; padding: 10px 12px; margin-top: 8px;
}
.inc-sub-header {
    display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px;
}
.inc-sub-id { font-size: 11px; font-weight: 700; color: #78350F; }
.inc-sub-notes { font-size: 12px; color: #78350F; margin-bottom: 8px; }
.inc-sub-files { font-size: 11px; color: var(--amber); margin-bottom: 8px; }
.inc-sub-lbl {
    font-size: 10px; color: #92400E; text-transform: uppercase;
    letter-spacing: .5px; font-weight: 700; margin-bottom: 6px;
}

/* ── Task chat ── */
.inc-chat {
    background: var(--surface2); border: 1px solid var(--border);
    border-radius: 8px; padding: 10px 12px; margin-top: 8px;
}
.inc-chat-label {
    font-size: 10px; color: var(--txt3); text-transform: uppercase;
    letter-spacing: .5px; font-weight: 700; margin-bottom: 7px;
}
.inc-chat-msg { font-size: 12px; color: var(--txt); margin-bottom: 6px; }
.inc-chat-msg strong { color: var(--txt2); }
.inc-chat-msg .chat-time { font-size: 10px; color: var(--txt3); margin-left: 6px; }
.inc-chat-empty { font-size: 12px; color: var(--txt3); text-align: center; padding: 6px 0; }
.inc-chat-row { display: flex; gap: 7px; align-items: center; margin-top: 7px; }
.inc-chat-input {
    flex: 1; border: 1px solid var(--border2); border-radius: 7px;
    padding: 7px 10px; font-size: 12px; color: var(--txt);
    background: var(--surface); outline: none; height: 34px;
    font-family: 'Cairo', sans-serif;
}
.inc-chat-input:focus { border-color: var(--accent); }
.inc-send-btn {
    width: 34px; height: 34px; border-radius: 7px;
    background: var(--green); color: #fff; border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.inc-send-btn:hover { background: var(--green-dark); }
.inc-send-btn svg { width: 13px; height: 13px; stroke: #fff; fill: none; stroke-width: 2; }

/* ── Right panel: metrics ── */
.inc-metrics-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-bottom: 1rem; }
.inc-metric {
    background: rgba(255,255,255,.07); border-radius: 8px;
    padding: 11px 13px; border: 1px solid rgba(255,255,255,.06);
}
.inc-metric-label { font-size: 10.5px; color: #6B7A99; margin-bottom: 4px; }
.inc-metric-val   { font-size: 24px; font-weight: 700; color: #5DCAA5; line-height: 1; }
.inc-metric-sub   { font-size: 10px; color: #4A5568; margin-top: 2px; }

.inc-prog-wrap { margin-bottom: .875rem; }
.inc-prog-wrap:last-of-type { margin-bottom: 0; }
.inc-prog-head { display: flex; justify-content: space-between; font-size: 11.5px; margin-bottom: 5px; }
.inc-prog-head span   { color: #6B7A99; }
.inc-prog-head strong { color: #5DCAA5; font-weight: 600; }
.inc-prog-bar  { height: 5px; background: rgba(255,255,255,.08); border-radius: 3px; overflow: hidden; }
.inc-prog-fill { height: 100%; border-radius: 3px; background: #1D9E75; }

.inc-stage-dots { display: flex; gap: 4px; margin-top: .875rem; }
.inc-dot { flex: 1; height: 4px; border-radius: 2px; }
.inc-dot.completed   { background: #1D9E75; }
.inc-dot.in_progress { background: rgba(26,86,219,.55); }
.inc-dot.not_started { background: rgba(220,80,80,.35); }

/* ── Activity log ── */
.inc-activity-item {
    font-size: 12px; color: var(--txt2); padding: 7px 0;
    border-bottom: 1px solid var(--border);
    display: flex; justify-content: space-between;
}
.inc-activity-item:last-child { border-bottom: none; }
.inc-activity-time { font-size: 10.5px; color: var(--txt3); white-space: nowrap; margin-left: 8px; }
</style>
