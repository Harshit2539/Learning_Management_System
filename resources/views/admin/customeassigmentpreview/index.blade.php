@extends('admin.layouts.app')

@section('title', 'Assignment Dashboard')

@push('styles_top')

<!-- Google Fonts -->

<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">

<style>
    :root {
        --bg-body: #f0f2f8;
        --bg-card: #ffffff;
        --bg-card-alt: #f8f9fe;
        --text-primary: #1e2235;
        --text-secondary: #5c6280;
        --text-muted: #9ba2b8;
        --border: #e4e7f0;
        --border-light: #f0f1f7;
        --accent-purple: #6c5ce7;
        --accent-purple-light: #a29bfe;
        --accent-blue: #0984e3;
        --accent-green: #00b894;
        --accent-green-dark: #00a381;
        --accent-orange: #f39c12;
        --accent-orange-dark: #e67e22;
        --accent-red: #e74c3c;
        --accent-red-light: #ffeaea;
        --shadow-card: 0 2px 12px rgba(30, 34, 53, 0.06);
        --shadow-card-hover: 0 8px 30px rgba(108, 92, 231, 0.12);
        --shadow-btn: 0 3px 10px rgba(108, 92, 231, 0.25);
        --radius: 14px;
        --radius-sm: 10px;
        --radius-xs: 7px;
    }

    * { box-sizing: border-box; }

    body {
        background: var(--bg-body);
        font-family: 'Inter', system-ui, -apple-system, sans-serif;
    }

    @keyframes fadeUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @keyframes scaleIn {
        from { opacity: 0; transform: scale(0.9); }
        to { opacity: 1; transform: scale(1); }
    }
    @keyframes pulse-ring {
        0% { box-shadow: 0 0 0 0 rgba(108, 92, 231, 0.3); }
        70% { box-shadow: 0 0 0 8px rgba(108, 92, 231, 0); }
        100% { box-shadow: 0 0 0 0 rgba(108, 92, 231, 0); }
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-6px); }
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    @keyframes tagIn {
        from { opacity: 0; transform: scale(0.8) translateY(4px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .anim-fade-up { animation: fadeUp 0.55s cubic-bezier(0.22, 1, 0.36, 1) forwards; opacity: 0; }
    .anim-scale { animation: scaleIn 0.45s cubic-bezier(0.22, 1, 0.36, 1) forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.06s; }
    .delay-2 { animation-delay: 0.12s; }
    .delay-3 { animation-delay: 0.18s; }
    .delay-4 { animation-delay: 0.24s; }
    .delay-5 { animation-delay: 0.35s; }

    /* ===== PAGE HEADER ===== */
    .page-header-wrapper {
        background: linear-gradient(135deg, #6c5ce7 0%, #0984e3 60%, #00b894 100%);
        border-radius: var(--radius);
        padding: 36px 40px;
        margin-bottom: 28px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 8px 40px rgba(108, 92, 231, 0.2);
    }
    .page-header-wrapper::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -5%;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, transparent 65%);
        border-radius: 50%;
    }
    .page-header-wrapper::after {
        content: '';
        position: absolute;
        bottom: -30%;
        left: 15%;
        width: 350px;
        height: 350px;
        background: radial-gradient(circle, rgba(255,255,255,0.05) 0%, transparent 65%);
        border-radius: 50%;
    }
    .page-header-inner {
        position: relative;
        z-index: 2;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 20px;
    }
    .page-header-left {
        display: flex;
        align-items: center;
        gap: 18px;
    }
    .page-header-icon-box {
        width: 60px;
        height: 60px;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(12px);
        border: 1px solid rgba(255,255,255,0.2);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        animation: float 4s ease-in-out infinite;
    }
    .page-header-icon-box svg { width: 30px; height: 30px; color: #fff; }
    .page-header-title {
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin: 0;
        letter-spacing: -0.5px;
        text-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .page-header-sub {
        font-size: 14px;
        color: rgba(255,255,255,0.7);
        margin-top: 4px;
        font-weight: 400;
    }
    .header-badge {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255,255,255,0.25);
        padding: 8px 20px;
        border-radius: 30px;
        color: #fff;
        font-size: 13px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .header-badge-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #55efc4;
        box-shadow: 0 0 8px #55efc4;
    }

    /* ===== STATS CARDS ===== */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 20px;
        margin-bottom: 28px;
    }
    .stat-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        padding: 24px;
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        display: flex;
        align-items: center;
        gap: 18px;
        transition: all 0.35s cubic-bezier(0.22, 1, 0.36, 1);
        cursor: default;
        position: relative;
        overflow: hidden;
    }
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        border-radius: 3px 3px 0 0;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-card-hover);
        border-color: transparent;
    }
    .stat-card:hover::before { opacity: 1; }
    .stat-card.blue::before { background: linear-gradient(90deg, #0984e3, #74b9ff); }
    .stat-card.green::before { background: linear-gradient(90deg, #00b894, #55efc4); }
    .stat-card.orange::before { background: linear-gradient(90deg, #f39c12, #fdcb6e); }
    .stat-card.purple::before { background: linear-gradient(90deg, #6c5ce7, #a29bfe); }

    .stat-icon {
        width: 52px;
        height: 52px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .stat-icon svg { width: 24px; height: 24px; }
    .stat-icon.blue { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #0984e3; }
    .stat-icon.green { background: linear-gradient(135deg, #d1fae5, #a7f3d0); color: #00b894; }
    .stat-icon.orange { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #f39c12; }
    .stat-icon.purple { background: linear-gradient(135deg, #ede9fe, #ddd6fe); color: #6c5ce7; }

    .stat-info { flex: 1; }
    .stat-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }
    .stat-number {
        font-size: 28px;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }

    /* ===== MODERN FILTER PANEL ===== */
    .filter-panel {
        background: var(--bg-card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        margin-bottom: 22px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }
    .filter-panel:hover {
        box-shadow: var(--shadow-card-hover);
    }

    /* Search Row */
    .filter-search-row {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px 24px 16px;
    }
    .search-box {
        flex: 1;
        position: relative;
    }
    .search-box-icon {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        color: var(--text-muted);
        transition: color 0.2s ease;
        pointer-events: none;
        z-index: 2;
    }
    .search-box-input {
        width: 100%;
        height: 48px;
        padding: 0 120px 0 48px;
        border: 2px solid var(--border);
        border-radius: var(--radius-sm);
        font-size: 14px;
        font-weight: 500;
        color: var(--text-primary);
        background: var(--bg-card-alt);
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
        font-family: 'Inter', sans-serif;
        outline: none;
    }
    .search-box-input:focus {
        border-color: var(--accent-purple);
        box-shadow: 0 0 0 4px rgba(108, 92, 231, 0.1), 0 4px 16px rgba(108, 92, 231, 0.08);
        background: #fff;
        height: 52px;
    }
    .search-box-input:focus ~ .search-box-icon {
        color: var(--accent-purple);
    }
    .search-box-input::placeholder {
        color: var(--text-muted);
        font-weight: 400;
    }
    .search-box-actions {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        align-items: center;
        gap: 6px;
        z-index: 2;
    }
    .search-box-clear {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: var(--radius-xs);
        background: transparent;
        color: var(--text-muted);
        cursor: pointer;
        display: none;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    .search-box-clear.visible { display: inline-flex; }
    .search-box-clear:hover { background: var(--border); color: var(--text-secondary); }
    .search-box-clear svg { width: 14px; height: 14px; }
    .search-box-btn {
        height: 36px;
        padding: 0 20px;
        border: none;
        border-radius: var(--radius-xs);
        background: linear-gradient(135deg, #6c5ce7, #0984e3);
        color: #fff;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        font-family: 'Inter', sans-serif;
        white-space: nowrap;
        letter-spacing: 0.2px;
        box-shadow: 0 2px 8px rgba(108, 92, 231, 0.25);
    }
    .search-box-btn svg { width: 14px; height: 14px; }
    .search-box-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 14px rgba(108, 92, 231, 0.35);
    }

    /* Filter Details Row */
    .filter-details-row {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 0 24px 20px;
        flex-wrap: wrap;
    }
    .filter-detail-group {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }
    .filter-detail-label {
        font-size: 10px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1px;
        padding-left: 2px;
    }
    .filter-detail-input-wrap {
        position: relative;
        display: flex;
        align-items: center;
    }
    .filter-detail-icon {
        position: absolute;
        left: 12px;
        width: 14px;
        height: 14px;
        color: var(--text-muted);
        pointer-events: none;
        transition: color 0.2s ease;
    }
    .filter-detail-input {
        height: 40px;
        padding: 0 14px 0 36px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-xs);
        font-size: 13px;
        font-weight: 500;
        color: var(--text-primary);
        background: var(--bg-card-alt);
        transition: all 0.25s ease;
        font-family: 'Inter', sans-serif;
        outline: none;
        min-width: 170px;
    }
    .filter-detail-input:focus {
        border-color: var(--accent-purple);
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.08);
        background: #fff;
    }
    .filter-detail-input:focus ~ .filter-detail-icon {
        color: var(--accent-purple);
    }
    .filter-detail-input::placeholder { color: var(--text-muted); font-weight: 400; }

    /* Date separator */
    .date-sep {
        height: 40px;
        display: flex;
        align-items: center;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        padding-top: 16px;
    }

    /* Status Pills */
    .status-pills {
        display: flex;
        align-items: flex-end;
        gap: 6px;
        padding-top: 16px;
    }
    .status-pill {
        height: 40px;
        padding: 0 16px;
        border: 1.5px solid var(--border);
        border-radius: 20px;
        background: var(--bg-card-alt);
        color: var(--text-secondary);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        font-family: 'Inter', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        letter-spacing: 0.2px;
    }
    .status-pill svg { width: 13px; height: 13px; }
    .status-pill:hover {
        border-color: var(--accent-purple-light);
        color: var(--accent-purple);
        background: rgba(108, 92, 231, 0.04);
    }
    .status-pill.active {
        background: linear-gradient(135deg, #6c5ce7, #0984e3);
        color: #fff;
        border-color: transparent;
        box-shadow: 0 2px 10px rgba(108, 92, 231, 0.3);
    }
    .status-pill.active svg { color: rgba(255,255,255,0.8); }

    /* Reset button in detail row */
    .filter-reset-btn {
        height: 40px;
        padding: 0 18px;
        border: 1.5px solid var(--border);
        border-radius: var(--radius-xs);
        background: transparent;
        color: var(--text-muted);
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s ease;
        font-family: 'Inter', sans-serif;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        white-space: nowrap;
        margin-left: auto;
    }
    .filter-reset-btn svg { width: 14px; height: 14px; }
    .filter-reset-btn:hover {
        border-color: var(--accent-red);
        color: var(--accent-red);
        background: var(--accent-red-light);
    }

    /* Active Filter Tags */
    .filter-tags {
        display: none;
        align-items: center;
        gap: 8px;
        padding: 0 24px 16px;
        flex-wrap: wrap;
    }
    .filter-tags.visible { display: flex; }
    .filter-tags-label {
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }
    .filter-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        animation: tagIn 0.3s cubic-bezier(0.22, 1, 0.36, 1) both;
        cursor: default;
    }
    .filter-tag-name { background: rgba(108, 92, 231, 0.1); color: #6c5ce7; border: 1px solid rgba(108, 92, 231, 0.2); }
    .filter-tag-date { background: rgba(9, 132, 227, 0.1); color: #0984e3; border: 1px solid rgba(9, 132, 227, 0.2); }
    .filter-tag-status { background: rgba(0, 184, 148, 0.1); color: #00b894; border: 1px solid rgba(0, 184, 148, 0.2); }
    .filter-tag-remove {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: none;
        background: rgba(0,0,0,0.08);
        color: inherit;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        padding: 0;
    }
    .filter-tag-remove:hover { background: rgba(0,0,0,0.15); }
    .filter-tag-remove svg { width: 10px; height: 10px; }

    .filter-divider {
        height: 1px;
        background: var(--border-light);
        margin: 0 24px;
    }
    .filter-divider.hidden { display: none; }

    /* ===== TABLE CARD ===== */
    .table-card {
        background: var(--bg-card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow-card);
        overflow: hidden;
    }
    .table-card-top {
        padding: 20px 28px;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        background: linear-gradient(to bottom, #fcfcff, var(--bg-card));
    }
    .table-card-heading {
        font-size: 16px;
        font-weight: 700;
        color: var(--text-primary);
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-card-heading svg { width: 20px; height: 20px; color: var(--accent-purple); }
    .result-count-badge {
        background: var(--accent-purple);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
        transition: all 0.3s ease;
    }
    .result-count-badge.filtered {
        background: var(--accent-blue);
    }

    /* ===== DATATABLES OVERRIDES ===== */
    .table-responsive { overflow-x: auto; }
    table.dataTable {
        border-collapse: separate !important;
        border-spacing: 0 !important;
        width: 100% !important;
        min-width: 1100px;
    }
    table.dataTable thead th {
        background: linear-gradient(to bottom, #f5f6fa, #eef0f7) !important;
        border-bottom: 2px solid var(--border) !important;
        color: var(--text-secondary) !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.8px !important;
        padding: 14px 16px !important;
        text-align: center !important;
        white-space: nowrap;
        font-family: 'Inter', sans-serif !important;
    }
    table.dataTable thead th:first-child { padding-left: 28px !important; }
    table.dataTable thead th:last-child { padding-right: 28px !important; }
    table.dataTable tbody td {
        padding: 16px !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        color: var(--text-primary) !important;
        border-bottom: 1px solid var(--border-light) !important;
        text-align: center !important;
        vertical-align: middle !important;
        font-family: 'Inter', sans-serif !important;
        transition: background 0.15s ease;
    }
    table.dataTable tbody td:first-child { padding-left: 28px !important; }
    table.dataTable tbody td:last-child { padding-right: 28px !important; }
    table.dataTable tbody tr { background: transparent !important; transition: all 0.2s ease; }
    table.dataTable tbody tr:hover td { background: var(--bg-card-alt) !important; }
    table.dataTable tbody tr.odd { background: transparent !important; }
    table.dataTable tbody tr.even { background: transparent !important; }

    .dataTables_wrapper .dataTables_paginate {
        padding: 16px 28px !important;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        border-top: 1px solid var(--border-light);
        background: linear-gradient(to bottom, var(--bg-card), #fcfcff);
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-xs) !important;
        background: var(--bg-card) !important;
        color: var(--text-secondary) !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        padding: 6px 14px !important;
        margin: 0 2px !important;
        transition: all 0.2s ease !important;
        font-family: 'Inter', sans-serif !important;
        cursor: pointer !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: var(--accent-purple) !important;
        color: #fff !important;
        border-color: var(--accent-purple) !important;
        box-shadow: 0 2px 8px rgba(108, 92, 231, 0.25) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #6c5ce7, #0984e3) !important;
        color: #fff !important;
        border-color: transparent !important;
        box-shadow: 0 3px 12px rgba(108, 92, 231, 0.3) !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.35 !important;
        cursor: default !important;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover {
        background: var(--bg-card) !important;
        color: var(--text-muted) !important;
        border-color: var(--border) !important;
        box-shadow: none !important;
    }
    .dataTables_wrapper .dataTables_info {
        padding: 14px 28px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: var(--text-muted) !important;
        font-family: 'Inter', sans-serif !important;
        border-top: 1px solid var(--border-light);
    }
    .dataTables_wrapper .dataTables_info b { color: var(--accent-purple) !important; font-weight: 700 !important; }
    .dataTables_wrapper .dataTables_length {
        padding: 14px 28px !important;
        font-size: 12px !important;
        font-weight: 500 !important;
        color: var(--text-secondary) !important;
        font-family: 'Inter', sans-serif !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1.5px solid var(--border) !important;
        border-radius: var(--radius-xs) !important;
        padding: 5px 10px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        color: var(--text-primary) !important;
        background: var(--bg-card-alt) !important;
        outline: none !important;
        font-family: 'Inter', sans-serif !important;
        cursor: pointer;
    }
    .dataTables_wrapper .dataTables_length select:focus {
        border-color: var(--accent-purple) !important;
        box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1) !important;
    }
    .dataTables_wrapper .dataTables_filter { display: none !important; }
    .dataTables_wrapper .dataTables_paginate { float: none !important; }
    .dataTables_wrapper .dataTables_info { float: none !important; text-align: center !important; }
    table.dataTable thead .sorting::after,
    table.dataTable thead .sorting_asc::after,
    table.dataTable thead .sorting_desc::after { opacity: 0.4 !important; color: var(--text-muted) !important; }
    table.dataTable thead .sorting_asc::after,
    table.dataTable thead .sorting_desc::after { opacity: 1 !important; color: var(--accent-purple) !important; }

    /* ===== ROW NUMBER ===== */
    .row-num {
        width: 32px; height: 32px; border-radius: var(--radius-xs);
        background: var(--bg-card-alt); border: 1px solid var(--border);
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: var(--text-muted);
    }

    /* ===== USER NAME ===== */
    .user-name-cell { display: flex; align-items: center; justify-content: center; gap: 10px; }
    .user-avatar {
        width: 34px; height: 34px; border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-size: 13px; font-weight: 700; color: #fff; flex-shrink: 0; text-transform: uppercase;
    }
    .user-name-text { font-weight: 600; color: var(--text-primary); white-space: nowrap; }

    /* ===== TITLE ===== */
    .assignment-title {
        font-weight: 600; color: var(--text-primary); max-width: 200px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: inline-block;
    }

    /* ===== BUTTONS ===== */
    .btn-dl {
        display: inline-flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 600;
        padding: 7px 14px; border-radius: var(--radius-xs); border: none; cursor: pointer;
        transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1); text-decoration: none;
        white-space: nowrap; position: relative; overflow: hidden;
    }
    .btn-dl::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(255,255,255,0.15), transparent); pointer-events: none; }
    .btn-dl svg { width: 13px; height: 13px; flex-shrink: 0; }
    .btn-dl-green { background: linear-gradient(135deg, #00b894, #00cec9); color: #fff; box-shadow: 0 2px 8px rgba(0, 184, 148, 0.3); }
    .btn-dl-green:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(0, 184, 148, 0.35); }
    .btn-dl-blue { background: linear-gradient(135deg, #0984e3, #74b9ff); color: #fff; box-shadow: 0 2px 8px rgba(9, 132, 227, 0.3); }
    .btn-dl-blue:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(9, 132, 227, 0.35); }

    /* ===== BADGES ===== */
    .badge-pill { font-size: 11px; font-weight: 600; padding: 5px 14px; border-radius: 20px; display: inline-flex; align-items: center; gap: 6px; letter-spacing: 0.2px; }
    .badge-na { background: #f1f3f8; color: #8891a5; border: 1px solid #e4e7ee; }
    .badge-pending { background: linear-gradient(135deg, #fff8f0, #fff1e6); color: #d35400; border: 1px solid #fde2c8; }
    .badge-pending .dot { width: 6px; height: 6px; border-radius: 50%; background: #e17055; animation: pulse-ring 1.8s ease-in-out infinite; }

    /* ===== DATE ===== */
    .date-cell { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 500; color: var(--text-secondary); background: var(--bg-card-alt); padding: 5px 11px; border-radius: var(--radius-xs); border: 1px solid var(--border); white-space: nowrap; }
    .date-cell svg { width: 12px; height: 12px; color: var(--text-muted); flex-shrink: 0; }
    .date-cell.overdue { background: var(--accent-red-light); color: var(--accent-red); border-color: #fed7d7; }
    .date-cell.overdue svg { color: var(--accent-red); }

    /* ===== MARKS ===== */
    .marks-display { font-size: 16px; font-weight: 800; color: var(--accent-purple); }
    .marks-label { font-size: 9px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; }

    /* ===== MARKS INPUT ===== */
    .marks-form-group { display: flex; align-items: center; gap: 6px; justify-content: center; }
    .marks-input-wrap { position: relative; }
    .marks-input-wrap::after { content: '/ ' attr(data-max); position: absolute; right: 8px; top: 50%; transform: translateY(-50%); font-size: 9px; color: var(--text-muted); font-weight: 600; pointer-events: none; }
    .marks-input { width: 72px; font-size: 12px !important; font-weight: 600; text-align: center; border: 1.5px solid var(--border); border-radius: var(--radius-xs); padding: 7px 28px 7px 8px !important; background: var(--bg-card-alt) !important; color: var(--text-primary) !important; transition: all 0.25s ease; font-family: 'Inter', sans-serif !important; outline: none !important; height: auto !important; margin: 0 !important; }
    .marks-input:focus { border-color: var(--accent-purple) !important; box-shadow: 0 0 0 3px rgba(108, 92, 231, 0.1) !important; background: #fff !important; }
    .marks-input.valid { border-color: var(--accent-green) !important; background: #f0fdf9 !important; }
    .marks-input.invalid { border-color: var(--accent-red) !important; background: #fff8f5 !important; }
    .marks-input::placeholder { color: var(--text-muted) !important; font-weight: 400 !important; }
    .btn-save-marks { width: 36px; height: 36px; border: none; border-radius: var(--radius-xs); background: linear-gradient(135deg, #6c5ce7, #a29bfe); color: #fff; cursor: pointer; display: inline-flex; align-items: center; justify-content: center; transition: all 0.25s ease; box-shadow: 0 2px 8px rgba(108, 92, 231, 0.3); flex-shrink: 0; position: relative; overflow: hidden; }
    .btn-save-marks::after { content: ''; position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(255,255,255,0.15), transparent); pointer-events: none; }
    .btn-save-marks:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(108, 92, 231, 0.4); }
    .btn-save-marks svg { width: 16px; height: 16px; }
    .btn-save-marks.loading { pointer-events: none; opacity: 0.7; }

    /* ===== TOAST ===== */
    .toast-notification { position: fixed; top: 24px; right: 24px; padding: 14px 24px; border-radius: var(--radius-sm); color: #fff; font-size: 13px; font-weight: 600; font-family: 'Inter', sans-serif; box-shadow: 0 10px 40px rgba(0,0,0,0.15); z-index: 99999; transform: translateX(calc(100% + 40px)); transition: transform 0.45s cubic-bezier(0.22, 1, 0.36, 1); display: flex; align-items: center; gap: 10px; }
    .toast-notification.show { transform: translateX(0); }
    .toast-notification.success { background: linear-gradient(135deg, #00b894, #00cec9); }
    .toast-notification.error { background: linear-gradient(135deg, #e17055, #d63031); }
    .toast-notification svg { width: 18px; height: 18px; flex-shrink: 0; }
    .dash-text { color: var(--text-muted); font-size: 16px; font-weight: 300; }

    /* ===== RESPONSIVE ===== */
    @media(max-width: 768px) {
        .page-header-wrapper { padding: 24px 20px; border-radius: var(--radius-sm); }
        .page-header-title { font-size: 20px; }
        .page-header-icon-box { width: 46px; height: 46px; }
        .page-header-icon-box svg { width: 22px; height: 22px; }
        .stats-grid { grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .stat-card { padding: 16px; }
        .stat-number { font-size: 22px; }
        .stat-icon { width: 42px; height: 42px; }
        .filter-search-row { padding: 16px 16px 12px; gap: 10px; }
        .filter-details-row { padding: 0 16px 16px; gap: 10px; }
        .filter-tags { padding: 0 16px 12px; }
        .filter-detail-input { min-width: 140px; }
        .status-pills { gap: 4px; }
        .status-pill { padding: 0 12px; font-size: 11px; }
        .table-card-top { padding: 16px 20px; }
        .marks-input { width: 58px; font-size: 11px !important; padding: 6px 24px 6px 6px !important; }
        .btn-dl { padding: 5px 10px; font-size: 10px; }
        .user-avatar { width: 28px; height: 28px; font-size: 11px; }
        .user-name-text { font-size: 12px; }
        .header-badge { display: none; }
        .search-box-input { height: 44px; font-size: 13px; }
        .filter-reset-btn { margin-left: 0; }
    }
</style>
@endpush


@section('content')
<div class="container py-4">

    {{-- PAGE HEADER --}}
    <div class="page-header-wrapper anim-fade-up">
        <div class="page-header-inner">
            <div class="page-header-left">
                <div class="page-header-icon-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                </div>
                <div>
                    <h1 class="page-header-title">Assignment Preview</h1>
                    <p class="page-header-sub">Manage submissions, evaluate marks & track deadlines</p>
                </div>
            </div>
            <div class="header-badge">
                <span class="header-badge-dot"></span>
                {{ $assignments->count() }} Records
            </div>
        </div>
    </div>

    {{-- STATS CARDS --}}
    <div class="stats-grid">
        <div class="stat-card blue anim-fade-up delay-1">
            <div class="stat-icon blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Total</div>
                <div class="stat-number">{{ $assignments->count() }}</div>
            </div>
        </div>
        <div class="stat-card green anim-fade-up delay-2">
            <div class="stat-icon green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Submitted</div>
                <div class="stat-number">{{ $assignments->whereNotNull('pdf_review')->count() }}</div>
            </div>
        </div>
        <div class="stat-card orange anim-fade-up delay-3">
            <div class="stat-icon orange">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Pending</div>
                <div class="stat-number">{{ $assignments->whereNull('pdf_review')->count() }}</div>
            </div>
        </div>
        <div class="stat-card purple anim-fade-up delay-4">
            <div class="stat-icon purple">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            </div>
            <div class="stat-info">
                <div class="stat-label">Evaluated</div>
                <div class="stat-number">{{ $assignments->whereNotNull('admin_marks')->count() }}</div>
            </div>
        </div>
    </div>

    {{-- MODERN FILTER PANEL --}}
    <div class="filter-panel anim-fade-up delay-4">
        {{-- Row 1: Main Search --}}
        <div class="filter-search-row">
            <div class="search-box">
                <svg class="search-box-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" id="filterSearch" class="search-box-input" placeholder="Search by student name or assignment title...">
                <div class="search-box-actions">
                    <button type="button" id="searchClearBtn" class="search-box-clear" title="Clear search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                        </svg>
                    </button>
                    <button type="button" id="btnApplyFilter" class="search-box-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                        </svg>
                        Search
                    </button>
                </div>
            </div>
        </div>

        {{-- Divider --}}
        <div class="filter-divider" id="filterDivider"></div>

        {{-- Active Filter Tags --}}
        <div class="filter-tags" id="filterTags">
            <span class="filter-tags-label">Active:</span>
            <div id="filterTagsContainer"></div>
        </div>

        {{-- Row 2: Date + Status + Reset --}}
        <div class="filter-details-row">
            <div class="filter-detail-group">
                <label class="filter-detail-label">From Date</label>
                <div class="filter-detail-input-wrap">
                    <input type="date" id="filterDateFrom" class="filter-detail-input">
                    <svg class="filter-detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>

            <span class="date-sep">to</span>

            <div class="filter-detail-group">
                <label class="filter-detail-label">To Date</label>
                <div class="filter-detail-input-wrap">
                    <input type="date" id="filterDateTo" class="filter-detail-input">
                    <svg class="filter-detail-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/>
                    </svg>
                </div>
            </div>

            <div class="status-pills" id="statusPills">
                <button type="button" class="status-pill active" data-status="">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    All
                </button>
                <button type="button" class="status-pill" data-status="submitted">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                    Submitted
                </button>
                <button type="button" class="status-pill" data-status="pending">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    Pending
                </button>
                <button type="button" class="status-pill" data-status="evaluated">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                    Evaluated
                </button>
            </div>

            <button type="button" id="btnResetFilter" class="filter-reset-btn" style="display:none;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"/>
                </svg>
                Clear All
            </button>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="table-card anim-fade-up delay-5">
        <div class="table-card-top">
            <div class="table-card-heading">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/>
                    <line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/>
                </svg>
                All Assignments
                <span class="result-count-badge" id="resultCount">{{ $assignments->count() }}</span>
            </div>
        </div>

        <div class="table-responsive">
            <table id="assignmentTable" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>User</th>
                        <th>Title</th>
                        <th>File</th>
                        <th>Submission</th>
                        <th>Total</th>
                        <th>Date</th>
                        <th>Deadline</th>
                        <th>Marks</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($assignments as $key => $assignment)
                    @php
                        $initials = substr($assignment->user_name ?? 'U', 0, 1);
                        $avatarColors = ['#6c5ce7','#0984e3','#00b894','#e17055','#fdcb6e','#e84393','#00cec9','#d63031'];
                        $colorIndex = ord(strtolower($initials)) % count($avatarColors);
                        $avatarBg = $avatarColors[$colorIndex];
                        $deadlineObj = $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline) : null;
                        $isOverdue = $deadlineObj ? $deadlineObj->isPast() : false;
                        $dateObj = $assignment->created_at ? \Carbon\Carbon::parse($assignment->created_at) : null;
                        $dateFormatted = $dateObj ? $dateObj->format('d M Y') : 'N/A';
                        $deadlineFormatted = $deadlineObj ? $deadlineObj->format('d M Y') : 'N/A';
                        $dateAttr = $dateObj ? $dateObj->format('Y-m-d') : '';
                        $hasSubmitted = !is_null($assignment->pdf_review);
                        $isEvaluated = !is_null($assignment->admin_marks);
                    @endphp
                    <tr
                        data-name="{{ strtolower($assignment->user_name ?? '') }}"
                        data-title="{{ strtolower($assignment->title ?? '') }}"
                        data-date="{{ $dateAttr }}"
                        data-status="{{ $isEvaluated ? 'evaluated' : ($hasSubmitted ? 'submitted' : 'pending') }}"
                    >
                        <td><span class="row-num">{{ $key + 1 }}</span></td>
                        <td>
                            <div class="user-name-cell">
                                <div class="user-avatar" style="background:{{ $avatarBg }}">{{ $initials }}</div>
                                <span class="user-name-text">{{ $assignment->user_name }}</span>
                            </div>
                        </td>
                        <td><span class="assignment-title" title="{{ $assignment->title }}">{{ $assignment->title }}</span></td>
                        <td>
                            @if($assignment->file)
                                <a href="{{ asset($assignment->file) }}" class="btn-dl btn-dl-green" download>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Download
                                </a>
                            @else
                                <span class="badge-pill badge-na">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($hasSubmitted)
                                <a href="{{ asset($assignment->pdf_review) }}" class="btn-dl btn-dl-blue" download>
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                                    Download
                                </a>
                            @else
                                <span class="badge-pill badge-pending"><span class="dot"></span>Pending</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <div class="marks-display">{{ $assignment->total_marks ?? 0 }}</div>
                                <div class="marks-label">marks</div>
                            </div>
                        </td>
                        <td>
                            <span class="date-cell">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                                {{ $dateFormatted }}
                            </span>
                        </td>
                        <td>
                            <span class="date-cell {{ $isOverdue ? 'overdue' : '' }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                {{ $deadlineFormatted }}
                            </span>
                        </td>
                        <td>
                            @if($hasSubmitted)
                                <form action="{{ route('admin.assignments.submitMarks') }}" method="POST" class="marks-form" data-max="{{ $assignment->total_marks }}">
                                    @csrf
                                    <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                                    <div class="marks-form-group">
                                        <div class="marks-input-wrap" data-max="{{ $assignment->total_marks ?? 0 }}">
                                            <input type="number" name="obtain_marks" class="marks-input" min="0" max="{{ $assignment->total_marks }}" placeholder="0" value="{{ $assignment->admin_marks ?? '' }}" required>
                                        </div>
                                        <button type="submit" class="btn-save-marks" title="Save Marks">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <span class="dash-text">—</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- TOAST --}}
<div id="toastNotif" class="toast-notification">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    <span id="toastMsg">Saved!</span>
</div>

@endsection


@push('scripts_bottom')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
 $(document).ready(function () {

    var currentStatus = '';
    var table = $('#assignmentTable').DataTable({
        responsive: false,
        pageLength: 10,
        ordering: true,
        searching: false,
        lengthMenu: [10, 25, 50, 100],
        language: {
            info: 'Showing <b>_START_</b> to <b>_END_</b> of <b>_TOTAL_</b> entries',
            lengthMenu: 'Show _MENU_ per page',
            paginate: { first: '« First', last: 'Last »', next: 'Next ›', previous: '‹ Prev' },
            zeroRecords: 'No matching records found',
            emptyTable: 'No assignments available'
        },
        drawCallback: function() {
            var info = this.api().page.info();
            $('#resultCount').text(info.recordsDisplay);
        }
    });

    // ── Check if any filter is active ──
    function hasActiveFilters() {
        return ($('#filterSearch').val().trim() !== '') ||
               ($('#filterDateFrom').val() !== '') ||
               ($('#filterDateTo').val() !== '') ||
               (currentStatus !== '');
    }

    // ── Update UI state based on filters ──
    function updateFilterUI() {
        var active = hasActiveFilters();
        var searchVal = $('#filterSearch').val().trim();

        // Show/hide clear button on search
        if (searchVal.length > 0) {
            $('#searchClearBtn').addClass('visible');
        } else {
            $('#searchClearBtn').removeClass('visible');
        }

        // Show/hide divider
        if (active) {
            $('#filterDivider').removeClass('hidden');
        } else {
            $('#filterDivider').addClass('hidden');
        }

        // Show/hide reset button
        if (active) {
            $('#btnResetFilter').show();
        } else {
            $('#btnResetFilter').hide();
        }

        // Update count badge style
        if (active) {
            $('#resultCount').addClass('filtered');
        } else {
            $('#resultCount').removeClass('filtered');
        }

        // Build active filter tags
        buildFilterTags(searchVal);
    }

    // ── Build active filter tags ──
    function buildFilterTags(searchVal) {
        var $container = $('#filterTagsContainer');
        var $wrapper = $('#filterTags');
        $container.empty();

        var dateFrom = $('#filterDateFrom').val();
        var dateTo = $('#filterDateTo').val();

        if (searchVal) {
            $container.append(
                '<span class="filter-tag filter-tag-name">' +
                    'Name: "' + searchVal + '"' +
                    '<button type="button" class="filter-tag-remove" data-clear="search">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                '</span>'
            );
        }

        if (dateFrom) {
            var fromFormatted = formatDateLabel(dateFrom);
            $container.append(
                '<span class="filter-tag filter-tag-date">' +
                    'From: ' + fromFormatted +
                    '<button type="button" class="filter-tag-remove" data-clear="dateFrom">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                '</span>'
            );
        }

        if (dateTo) {
            var toFormatted = formatDateLabel(dateTo);
            $container.append(
                '<span class="filter-tag filter-tag-date">' +
                    'To: ' + toFormatted +
                    '<button type="button" class="filter-tag-remove" data-clear="dateTo">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                '</span>'
            );
        }

        if (currentStatus) {
            var statusLabel = currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1);
            $container.append(
                '<span class="filter-tag filter-tag-status">' +
                    statusLabel +
                    '<button type="button" class="filter-tag-remove" data-clear="status">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>' +
                    '</button>' +
                '</span>'
            );
        }

        if ($container.children().length > 0) {
            $wrapper.addClass('visible');
        } else {
            $wrapper.removeClass('visible');
        }
    }

    // ── Format date for tag label ──
    function formatDateLabel(dateStr) {
        var d = new Date(dateStr + 'T00:00:00');
        var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
        return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
    }

    // ── Core filter logic ──
    function applyFilters() {
        var search = $('#filterSearch').val().toLowerCase().trim();
        var dateFrom = $('#filterDateFrom').val();
        var dateTo = $('#filterDateTo').val();
        var status = currentStatus;

        table.rows().every(function() {
            var $row = $(this.node());
            var matchSearch = true;
            var matchDate = true;
            var matchStatus = true;

            // Name / Title search
            if (search) {
                var name = $row.attr('data-name') || '';
                var title = $row.attr('data-title') || '';
                matchSearch = name.indexOf(search) !== -1 || title.indexOf(search) !== -1;
            }

            // Date range filter
            if (dateFrom || dateTo) {
                var rowDate = $row.attr('data-date') || '';
                if (dateFrom && rowDate < dateFrom) matchDate = false;
                if (dateTo && rowDate > dateTo) matchDate = false;
            }

            // Status filter
            if (status) {
                matchStatus = $row.attr('data-status') === status;
            }

            if (matchSearch && matchDate && matchStatus) {
                $row.show().addClass('filter-visible');
            } else {
                $row.hide().removeClass('filter-visible');
            }
        });

        table.rows().search(function() {
            return $(this.node()).hasClass('filter-visible');
        }).draw();

        var visibleCount = table.rows('.filter-visible').count();
        $('#resultCount').text(visibleCount);

        updateFilterUI();
    }

    // ── Reset all filters ──
    function resetAll() {
        $('#filterSearch').val('');
        $('#filterDateFrom').val('');
        $('#filterDateTo').val('');
        currentStatus = '';

        // Reset status pills
        $('.status-pill').removeClass('active');
        $('.status-pill[data-status=""]').addClass('active');

        // Show all rows and redraw
        table.search('').draw();
        table.rows().every(function() {
            $(this.node()).show().addClass('filter-visible');
        });
        table.draw();
        $('#resultCount').text(table.rows().count());

        updateFilterUI();
    }

    // ── Search input events ──
    var searchTimer;
    $('#filterSearch').on('input', function() {
        clearTimeout(searchTimer);
        var val = $(this).val();
        if (val.trim() === '') {
            // If cleared, immediately apply (shows all with other filters)
            applyFilters();
        } else {
            searchTimer = setTimeout(applyFilters, 300);
        }
    });

    $('#filterSearch').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            clearTimeout(searchTimer);
            applyFilters();
        }
    });

    // Clear search button
    $('#searchClearBtn').on('click', function() {
        $('#filterSearch').val('').focus();
        applyFilters();
    });

    // Search button click
    $('#btnApplyFilter').on('click', function() {
        clearTimeout(searchTimer);
        applyFilters();
    });

    // ── Date filter events ──
    $('#filterDateFrom, #filterDateTo').on('change', function() {
        applyFilters();
    });

    // ── Status pill clicks ──
    $(document).on('click', '.status-pill', function() {
        var status = $(this).data('status');
        currentStatus = status;

        $('.status-pill').removeClass('active');
        $(this).addClass('active');

        applyFilters();
    });

    // ── Individual tag remove buttons ──
    $(document).on('click', '.filter-tag-remove', function(e) {
        e.stopPropagation();
        var clearType = $(this).data('clear');

        switch(clearType) {
            case 'search':
                $('#filterSearch').val('');
                break;
            case 'dateFrom':
                $('#filterDateFrom').val('');
                break;
            case 'dateTo':
                $('#filterDateTo').val('');
                break;
            case 'status':
                currentStatus = '';
                $('.status-pill').removeClass('active');
                $('.status-pill[data-status=""]').addClass('active');
                break;
        }

        applyFilters();
    });

    // ── Reset all button ──
    $('#btnResetFilter').on('click', function() {
        resetAll();
    });

    // ── Marks input validation ──
    $(document).on('input', 'input[name="obtain_marks"]', function() {
        var max = parseInt(this.max) || 0;
        var val = parseInt(this.value);
        if (isNaN(val) || val < 0) {
            this.value = '';
            $(this).removeClass('valid').addClass('invalid');
        } else if (val > max) {
            this.value = max;
            $(this).removeClass('invalid').addClass('valid');
        } else {
            $(this).removeClass('invalid valid');
        }
    });
    $(document).on('blur', 'input[name="obtain_marks"]', function() {
        var max = parseInt(this.max) || 0;
        var val = parseInt(this.value);
        if (!isNaN(val) && val >= 0 && val <= max) {
            $(this).removeClass('invalid').addClass('valid');
        } else if (this.value !== '') {
            $(this).removeClass('valid').addClass('invalid');
        }
    });
    $(document).on('focus', 'input[name="obtain_marks"]', function() {
        $(this).removeClass('valid invalid');
    });

    // ── AJAX form submit ──
    $(document).on('submit', '.marks-form', function(e) {
        e.preventDefault();
        var form = this;
        var input = $(form).find('input[name="obtain_marks"]');
        var btn = $(form).find('.btn-save-marks');
        var max = parseInt(input.attr('max')) || 0;
        var val = parseInt(input.val());

        if (isNaN(val) || val < 0 || val > max) {
            showToast('Please enter valid marks (0 – ' + max + ')', 'error');
            return;
        }

        var originalSVG = btn.html();
        btn.addClass('loading').html('<svg style="width:16px;height:16px;animation:spin 0.7s linear infinite" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M21 12a9 9 0 11-6.219-8.56"/></svg>');

        $.ajax({
            url: $(form).attr('action'),
            method: 'POST',
            data: new FormData(form),
            processData: false,
            contentType: false,
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            success: function(res) {
                if (res.success || res.status === 'success') {
                    showToast('Marks saved successfully!', 'success');
                    input.removeClass('invalid').addClass('valid');
                } else {
                    showToast(res.message || 'Something went wrong.', 'error');
                }
            },
            error: function() { form.submit(); },
            complete: function() { btn.removeClass('loading').html(originalSVG); }
        });
    });

    // ── Toast ──
    function showToast(message, type) {
        var toast = $('#toastNotif');
        var msg = $('#toastMsg');
        msg.text(message);
        toast.removeClass('success error').addClass(type);
        var iconSvg = type === 'success'
            ? '<polyline points="20 6 9 17 4 12"/>'
            : '<circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>';
        toast.find('svg').html(iconSvg);
        toast.addClass('show');
        setTimeout(function() { toast.removeClass('show'); }, 3500);
    }
});
</script>

@endpush