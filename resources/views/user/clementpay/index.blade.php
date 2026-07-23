@extends('layouts.app')

@section('title', 'Clementpay | Clementine')

@section('content')
<style>
    /* ClementPay Page Styles — all critical layout uses inline or scoped CSS to avoid Tailwind resolution failures */
    #cpay-root {
        width: 100%;
        background: #ffffff;
        position: relative;
        padding-top: 80px;
    }

    /* Telemetry bars */
    .cpay-tele-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px 24px;
        border-bottom: 1px solid rgba(0,0,0,0.12);
        font-family: 'IBM Plex Sans', monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: #000;
        position: relative;
        z-index: 30;
        background: #fff;
    }
    .cpay-status-bar {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 12px 24px;
        border-bottom: 1px solid rgba(0,0,0,0.12);
        font-family: 'IBM Plex Sans', monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: rgba(0,0,0,0.5);
        background: #FAFAFA;
        position: relative;
        z-index: 30;
    }

    /* Main grid */
    .cpay-grid {
        width: 100%;
        display: grid;
        grid-template-columns: 1fr;
        background: #fff;
        min-height: 800px;
    }
    @media (min-width: 1024px) {
        .cpay-grid {
            grid-template-columns: 40% 60%;
        }
        .cpay-left {
            border-right: 1px solid rgba(0,0,0,0.12);
            border-bottom: none;
        }
    }

    /* Left panel */
    .cpay-left {
        display: flex;
        flex-direction: column;
        position: relative;
        border-bottom: 1px solid rgba(0,0,0,0.12);
        background: #fff;
        width: 100%;
    }

    /* Hero section */
    .cpay-hero {
        display: flex;
        flex-direction: column;
        padding: 24px;
        border-bottom: 1px solid rgba(0,0,0,0.12);
        width: 100%;
    }
    @media (min-width: 768px) {
        .cpay-hero { padding: 80px; }
        .cpay-tele-bar { padding: 24px 32px; }
    }

    .cpay-headline {
        font-family: 'Satoshi', sans-serif;
        font-size: clamp(4.5rem, 10vw, 7.5rem);
        line-height: 0.8;
        letter-spacing: -0.02em;
        text-transform: uppercase;
        color: #000;
        margin-bottom: 48px;
    }

    .cpay-manifesto-line {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 13px;
        color: rgba(0,0,0,0.75);
        text-transform: uppercase;
        letter-spacing: 0.15em;
        line-height: 2;
    }
    @media (min-width: 768px) {
        .cpay-manifesto-line { font-size: 14px; }
    }

    /* Allocation form area */
    .cpay-form-area {
        display: flex;
        flex-direction: column;
        padding: 24px;
        background: #FAFAFA;
        flex-grow: 1;
        justify-content: center;
        position: relative;
        width: 100%;
        z-index: 20;
    }
    @media (min-width: 768px) {
        .cpay-form-area { padding: 80px; }
    }

    .cpay-grid-bg {
        position: absolute;
        inset: 0;
        pointer-events: none;
        opacity: 0.3;
        background-size: 10% 10%;
        background-image:
            linear-gradient(to right, #e5e5e5 1px, transparent 1px),
            linear-gradient(to bottom, #e5e5e5 1px, transparent 1px);
    }

    /* Form */
    .cpay-form {
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 448px;
        position: relative;
        z-index: 30;
    }

    .cpay-form-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 32px;
    }
    .cpay-form-title .dot {
        width: 8px;
        height: 8px;
        background: #000;
        border-radius: 50%;
        animation: cpay-pulse 2s ease-in-out infinite;
    }
    @keyframes cpay-pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.4; }
    }
    .cpay-form-title h2 {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #000;
        margin: 0;
    }

    .cpay-input-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
        margin-bottom: 48px;
        width: 100%;
    }
    .cpay-input-group label {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: rgba(0,0,0,0.5);
    }
    .cpay-input-wrap {
        position: relative;
        width: 100%;
        border-bottom: 1px solid rgba(0,0,0,0.2);
        transition: border-color 0.3s ease;
    }
    .cpay-input-wrap:focus-within {
        border-color: #000;
    }
    .cpay-input-wrap .dollar {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        font-family: 'IBM Plex Sans', monospace;
        color: rgba(0,0,0,0.35);
        font-size: 24px;
        pointer-events: none;
    }
    .cpay-input {
        width: 100%;
        padding: 16px 0 16px 40px;
        font-size: 24px;
        font-family: 'IBM Plex Sans', monospace;
        color: #000;
        background: transparent;
        border: none;
        outline: none;
        -webkit-appearance: none;
        -moz-appearance: textfield;
        border-radius: 0 !important;
    }
    .cpay-input::placeholder {
        color: rgba(0,0,0,0.15);
    }
    .cpay-input::-webkit-inner-spin-button,
    .cpay-input::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    .cpay-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 16px;
        width: 100%;
        padding: 20px 24px;
        background: #000;
        color: #fff;
        border: 1px solid #000;
        font-family: 'Satoshi', sans-serif;
        font-size: 12px;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        z-index: 20;
    }
    .cpay-submit:hover {
        background: transparent;
        color: #000;
    }
    .cpay-submit:active {
        transform: scale(0.98);
    }
    .cpay-submit .arrow {
        transition: transform 0.3s ease;
    }
    .cpay-submit:hover .arrow {
        transform: translateX(8px);
    }

    /* Right panel */
    .cpay-right {
        display: flex;
        flex-direction: column;
        position: relative;
        background: #fff;
        width: 100%;
    }

    /* Balance area */
    .cpay-balance {
        padding: 24px;
        border-bottom: 1px solid rgba(0,0,0,0.12);
        display: flex;
        flex-direction: column;
        justify-content: center;
        min-height: 250px;
        width: 100%;
    }
    @media (min-width: 768px) {
        .cpay-balance { padding: 80px; min-height: 300px; }
    }
    .cpay-balance-label {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.3em;
        color: rgba(0,0,0,0.4);
        margin-bottom: 24px;
        display: block;
    }
    .cpay-balance-value {
        font-family: 'IBM Plex Sans', monospace;
        font-size: clamp(3.5rem, 8vw, 7rem);
        letter-spacing: -0.05em;
        color: #000;
        line-height: 1;
        word-break: break-all;
    }

    /* Audit log */
    .cpay-log-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 24px;
        border-bottom: 1px solid rgba(0,0,0,0.12);
        background: #fff;
        width: 100%;
    }
    @media (min-width: 768px) {
        .cpay-log-head { padding: 24px 32px; }
    }
    .cpay-log-head h3 {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 12px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: #000;
        margin: 0;
    }
    .cpay-log-head .count {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: rgba(0,0,0,0.35);
    }

    /* Transaction rows */
    .cpay-tx-list {
        display: flex;
        flex-direction: column;
        width: 100%;
        flex-grow: 1;
        position: relative;
    }
    .cpay-tx {
        display: flex;
        flex-direction: column;
        padding: 32px 24px;
        border-bottom: 1px solid rgba(0,0,0,0.06);
        transition: border-color 0.3s ease;
        width: 100%;
    }
    .cpay-tx:hover {
        border-color: rgba(0,0,0,0.25);
    }
    @media (min-width: 640px) {
        .cpay-tx {
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
        }
    }
    @media (min-width: 768px) {
        .cpay-tx { padding: 32px; }
    }
    .cpay-tx-info {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
        padding-right: 16px;
        flex-grow: 1;
    }
    .cpay-tx-meta {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .cpay-tx-type {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2em;
    }
    .cpay-tx-status {
        display: flex;
        align-items: center;
        gap: 6px;
        font-family: 'IBM Plex Sans', monospace;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.15em;
    }
    .cpay-tx-status .dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
    }
    .cpay-tx-desc {
        font-family: 'IBM Plex Sans', sans-serif;
        font-size: 13px;
        color: rgba(0,0,0,0.75);
        word-break: break-word;
        max-width: 448px;
    }
    @media (min-width: 768px) {
        .cpay-tx-desc { font-size: 14px; }
    }
    .cpay-tx-time {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 9px;
        color: rgba(0,0,0,0.35);
        letter-spacing: 0.15em;
    }
    .cpay-tx-amount {
        display: flex;
        flex-direction: column;
        margin-top: 16px;
        flex-shrink: 0;
    }
    @media (min-width: 640px) {
        .cpay-tx-amount {
            align-items: flex-end;
            margin-top: 0;
        }
    }
    .cpay-tx-amount span {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 18px;
    }
    @media (min-width: 768px) {
        .cpay-tx-amount span { font-size: 20px; }
    }

    /* Empty state */
    .cpay-empty {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding: 128px 24px;
        text-align: center;
        width: 100%;
    }
    .cpay-empty-line {
        width: 1px;
        height: 48px;
        background: rgba(0,0,0,0.15);
        margin-bottom: 24px;
    }
    .cpay-empty span {
        font-family: 'IBM Plex Sans', monospace;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: rgba(0,0,0,0.35);
    }

    /* Pagination */
    .cpay-pagination-wrap {
        padding: 24px;
        border-top: 1px solid rgba(0,0,0,0.12);
        width: 100%;
    }
    @media (min-width: 768px) {
        .cpay-pagination-wrap { padding: 24px 32px; }
    }

    /* Animation initial states — elements are VISIBLE by default, GSAP hides them before animating */
    .cpay-anim-fade {
        opacity: 1;
    }
    .cpay-anim-slide {
        opacity: 1;
        transform: translateY(0);
    }
</style>

<div id="cpay-root">

    {{-- Top Telemetry Bar --}}
    <div class="cpay-tele-bar">
        <span>[ PRIVATE TREASURY PROTOCOL ]</span>
        <span style="color: rgba(0,0,0,0.4); display: none;" class="hidden sm:inline-block">SYS.CPAY.2.0</span>
    </div>

    {{-- Status Bar --}}
    <div class="cpay-status-bar">
        BALANCE STATUS: <span style="margin-left: 12px; font-weight: 500; color: #000;" class="cpay-anim-fade" data-cpay-anim="fade">AUTHORIZED</span>
    </div>

    {{-- Main Grid --}}
    <div class="cpay-grid">

        {{-- LEFT: Treasury Info & Allocation --}}
        <div class="cpay-left">

            {{-- Hero Typography --}}
            <div class="cpay-hero">
                <h1 class="cpay-headline">
                    <span class="cpay-anim-slide" data-cpay-anim="word" style="display: block;">CLEMENT</span>
                    <span class="cpay-anim-slide" data-cpay-anim="word" style="display: block;">PAY.</span>
                </h1>

                <div style="max-width: 384px;">
                    <div class="cpay-manifesto-line cpay-anim-slide" data-cpay-anim="line">SECURE LEDGER.</div>
                    <div class="cpay-manifesto-line cpay-anim-slide" data-cpay-anim="line">PRIVATE ACQUISITIONS.</div>
                    <div class="cpay-manifesto-line cpay-anim-slide" data-cpay-anim="line" style="margin-top: 24px;">AUTHORIZE YOUR FUNDS</div>
                    <div class="cpay-manifesto-line cpay-anim-slide" data-cpay-anim="line">TO ACCESS THE DROP.</div>
                </div>
            </div>

            {{-- Allocation Form --}}
            <div class="cpay-form-area">
                <div class="cpay-grid-bg"></div>

                <div style="width: 100%; display: flex; justify-content: flex-start;">
                    <form action="{{ route('clementpay.topup') }}" method="POST" class="cpay-form cpay-anim-fade" data-cpay-anim="form" id="cpay-topup-form">
                        @csrf

                        <div class="cpay-form-title">
                            <span class="dot"></span>
                            <h2>ALLOCATE FUNDS</h2>
                        </div>

                        <div class="cpay-input-group">
                            <label for="amount">AMOUNT (USD)</label>
                            <div class="cpay-input-wrap">
                                <span class="dollar">$</span>
                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       min="100"
                                       step="1"
                                       required
                                       placeholder="100.00"
                                       class="cpay-input"
                                       autocomplete="off">
                            </div>
                        </div>

                        <button type="submit" class="cpay-submit">
                            <span>AUTHORIZE TRANSFER</span>
                            <span class="material-symbols-outlined arrow" style="font-size: 14px;">arrow_forward</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- RIGHT: Balance & Audit Log --}}
        <div class="cpay-right">

            {{-- Balance --}}
            <div class="cpay-balance cpay-anim-fade" data-cpay-anim="balance">
                <span class="cpay-balance-label">CURRENT BALANCE</span>
                <div class="cpay-balance-value">
                    ${{ number_format(auth()->user()->clementpay_balance, 2) }}
                </div>
            </div>

            {{-- Audit Log Header --}}
            <div class="cpay-log-head cpay-anim-fade" data-cpay-anim="loghead">
                <h3>AUDIT LOG</h3>
                <span class="count">RECORDS: {{ $transactions->total() }}</span>
            </div>

            {{-- Transactions --}}
            <div class="cpay-tx-list">
                @forelse($transactions as $index => $tx)
                <div class="cpay-tx cpay-anim-slide" data-cpay-anim="tx">

                    <div class="cpay-tx-info">
                        <div class="cpay-tx-meta">
                            <span class="cpay-tx-type" style="color: {{ $tx->status === 'success' ? '#000' : 'rgba(0,0,0,0.4)' }};">
                                {{ $tx->type }}
                            </span>
                            <span class="cpay-tx-status" style="color: {{ $tx->status === 'success' ? '#00B050' : 'rgba(0,0,0,0.4)' }};">
                                <span class="dot" style="background: {{ $tx->status === 'success' ? '#00B050' : 'rgba(0,0,0,0.25)' }};"></span>
                                {{ $tx->status }}
                            </span>
                        </div>
                        <span class="cpay-tx-desc">{{ $tx->description }}</span>
                        <span class="cpay-tx-time">{{ $tx->created_at->format('Y.m.d H:i:s') }}</span>
                    </div>

                    <div class="cpay-tx-amount">
                        <span style="color: {{ $tx->amount > 0 ? '#000' : 'rgba(0,0,0,0.4)' }};">
                            {{ $tx->amount > 0 ? '+' : '' }}${{ number_format(abs($tx->amount), 2) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="cpay-empty cpay-anim-fade" data-cpay-anim="tx">
                    <div class="cpay-empty-line"></div>
                    <span>NO RECORDS FOUND IN LEDGER</span>
                </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($transactions->hasPages())
                <div class="cpay-pagination-wrap cpay-anim-fade" data-cpay-anim="pagination">
                    {{ $transactions->links() }}
                </div>
            @endif

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // Bail gracefully if GSAP is not available — page stays fully visible and interactive
    if (typeof gsap === 'undefined') return;

    // Set initial animation states via GSAP (NOT inline styles)
    // This way, if GSAP fails to load, everything is visible by default
    gsap.set('[data-cpay-anim="word"]', {
        opacity: 0,
        y: 20,
        letterSpacing: '0.2em'
    });
    gsap.set('[data-cpay-anim="line"]', {
        opacity: 0,
        y: 10
    });
    gsap.set('[data-cpay-anim="form"]', {
        opacity: 0
    });
    gsap.set('[data-cpay-anim="balance"]', {
        opacity: 0
    });
    gsap.set('[data-cpay-anim="loghead"]', {
        opacity: 0
    });
    gsap.set('[data-cpay-anim="tx"]', {
        opacity: 0,
        y: 10
    });
    gsap.set('[data-cpay-anim="pagination"]', {
        opacity: 0
    });

    // Build timeline
    const tl = gsap.timeline({ delay: 0.1 });

    // 1. Headline words
    tl.to('[data-cpay-anim="word"]', {
        y: 0,
        opacity: 1,
        letterSpacing: '0em',
        duration: 0.8,
        stagger: 0.1,
        ease: 'power3.out'
    }, 0);

    // 2. Manifesto lines
    tl.to('[data-cpay-anim="line"]', {
        y: 0,
        opacity: 1,
        duration: 0.6,
        stagger: 0.05,
        ease: 'power2.out'
    }, 0.4);

    // 3. Form reveal
    tl.to('[data-cpay-anim="form"]', {
        opacity: 1,
        duration: 0.8,
        ease: 'power2.out'
    }, 0.6);

    // 4. Balance area
    tl.to('[data-cpay-anim="balance"]', {
        opacity: 1,
        duration: 1,
        ease: 'power2.out'
    }, 0.3);

    // 5. Log header
    tl.to('[data-cpay-anim="loghead"]', {
        opacity: 1,
        duration: 0.5,
        ease: 'power2.out'
    }, 0.5);

    // 6. Transaction rows
    tl.to('[data-cpay-anim="tx"]', {
        y: 0,
        opacity: 1,
        duration: 0.5,
        stagger: 0.05,
        ease: 'power2.out'
    }, 0.6);

    // 7. Pagination
    if (document.querySelector('[data-cpay-anim="pagination"]')) {
        tl.to('[data-cpay-anim="pagination"]', {
            opacity: 1,
            duration: 0.5,
            ease: 'none'
        }, 0.8);
    }
});
</script>
@endsection
