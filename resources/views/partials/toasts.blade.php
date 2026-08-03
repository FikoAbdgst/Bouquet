@once
@php
    $toasts = [];

    foreach (['success', 'error', 'warning', 'info'] as $type) {
        $value = session($type);

        if ($value === null) {
            continue;
        }

        if (is_array($value)) {
            foreach ($value as $message) {
                $toasts[] = ['type' => $type, 'message' => $message];
            }
        } else {
            $toasts[] = ['type' => $type, 'message' => $value];
        }
    }
@endphp

<div id="toast-container" role="region" aria-live="polite"
     class="fixed top-20 right-4 left-4 sm:left-auto sm:right-6 sm:w-96 z-[100] flex flex-col items-stretch gap-2.5 pointer-events-none">
    @foreach($toasts as $index => $toast)
        @php
            $type = $toast['type'];
            $theme = [
                'success' => ['chip' => 'bg-[#FDE7EE] text-[#D37897]', 'border' => 'border-[#F6D3DE]', 'bar' => 'bg-[#D37897]', 'label' => 'Berhasil'],
                'error'   => ['chip' => 'bg-red-50 text-red-500',      'border' => 'border-red-100',    'bar' => 'bg-red-400', 'label' => 'Gagal'],
                'warning' => ['chip' => 'bg-amber-50 text-amber-600',  'border' => 'border-amber-100',  'bar' => 'bg-amber-400', 'label' => 'Perhatian'],
                'info'    => ['chip' => 'bg-sky-50 text-sky-600',      'border' => 'border-sky-100',    'bar' => 'bg-sky-400', 'label' => 'Info'],
            ][$type];
            $durations = ['success' => 4500, 'error' => 7000, 'warning' => 6000, 'info' => 4500];
            $duration = $durations[$type];
            $stagger = $index * 120;
        @endphp
        <div data-toast="{{ $type }}" data-duration="{{ $duration }}" data-stagger="{{ $stagger }}"
             role="status"
             class="toast pointer-events-auto relative flex items-start gap-3 overflow-hidden rounded-xl bg-white border {{ $theme['border'] }} pl-5 pr-3 py-3 shadow-[0_12px_32px_-12px_rgba(51,65,58,0.22)]"
             style="animation-delay: {{ $stagger }}ms;">
            <span class="absolute inset-y-0 left-0 w-1 {{ $theme['bar'] }}"></span>
            <span class="flex-shrink-0 w-8 h-8 rounded-full {{ $theme['chip'] }} flex items-center justify-center mt-0.5">
                @if($type === 'success')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                @elseif($type === 'error')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                @elseif($type === 'warning')
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                @else
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                @endif
            </span>
            <div class="flex-1 min-w-0">
                <p class="text-[10px] font-semibold tracking-[0.18em] uppercase text-[#6E8577]">{{ $theme['label'] }}</p>
                <p class="text-[13px] font-medium text-[#33413A] leading-snug mt-1">{{ $toast['message'] }}</p>
            </div>
            <button type="button" data-toast-close
                    class="flex-shrink-0 w-7 h-7 rounded-full text-[#C9A9B4] hover:text-[#33413A] hover:bg-[#F1F0EA] flex items-center justify-center transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <span class="toast-progress absolute bottom-0 left-0 right-0 h-[3px] {{ $theme['bar'] }} opacity-60"
                  style="animation-duration: {{ $duration }}ms; animation-delay: {{ $stagger }}ms;"></span>
        </div>
    @endforeach
</div>

<style>
    @keyframes toast-in {
        from { opacity: 0; transform: translateX(32px) scale(0.97); }
        to   { opacity: 1; transform: translateX(0) scale(1); }
    }
    @keyframes toast-out {
        from { opacity: 1; transform: translateX(0) scale(1); }
        to   { opacity: 0; transform: translateX(32px) scale(0.97); }
    }
    @keyframes toast-progress {
        from { width: 100%; }
        to   { width: 0%; }
    }
    .toast {
        animation: toast-in .4s cubic-bezier(.22, 1, .36, 1) both;
    }
    .toast.toast-out {
        animation: toast-out .3s ease forwards;
    }
    .toast-progress {
        width: 100%;
        animation-name: toast-progress;
        animation-timing-function: linear;
        animation-fill-mode: both;
    }
</style>

<script>
    (function() {
        var THEMES = {
            success: { chip: 'bg-[#FDE7EE] text-[#D37897]', border: 'border-[#F6D3DE]', bar: 'bg-[#D37897]', label: 'Berhasil', duration: 4500, icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>' },
            error:   { chip: 'bg-red-50 text-red-500',      border: 'border-red-100',    bar: 'bg-red-400', label: 'Gagal', duration: 7000, icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' },
            warning: { chip: 'bg-amber-50 text-amber-600',  border: 'border-amber-100',  bar: 'bg-amber-400', label: 'Perhatian', duration: 6000, icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>' },
            info:    { chip: 'bg-sky-50 text-sky-600',      border: 'border-sky-100',    bar: 'bg-sky-400', label: 'Info', duration: 4500, icon: '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' }
        };

        var container = document.getElementById('toast-container');

        function dismiss(el) {
            if (el.dataset.closing) {
                return;
            }
            el.dataset.closing = '1';
            el.style.animationDelay = '0s';
            el.classList.add('toast-out');
            el.addEventListener('animationend', function() {
                el.remove();
            }, { once: true });
        }

        function wire(el, totalMs) {
            var closeBtn = el.querySelector('[data-toast-close]');

            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    dismiss(el);
                });
            }

            setTimeout(function() {
                dismiss(el);
            }, totalMs);
        }

        window.BuketToast = {
            show: function(type, message) {
                var theme = THEMES[type] || THEMES.info;
                var el = document.createElement('div');

                el.setAttribute('data-toast', type);
                el.setAttribute('role', 'status');
                el.className = 'toast pointer-events-auto relative flex items-start gap-3 overflow-hidden rounded-xl bg-white border ' + theme.border + ' pl-5 pr-3 py-3 shadow-[0_12px_32px_-12px_rgba(51,65,58,0.22)]';
                el.innerHTML =
                    '<span class="absolute inset-y-0 left-0 w-1 ' + theme.bar + '"></span>' +
                    '<span class="flex-shrink-0 w-8 h-8 rounded-full ' + theme.chip + ' flex items-center justify-center mt-0.5">' + theme.icon + '</span>' +
                    '<div class="flex-1 min-w-0">' +
                        '<p class="text-[10px] font-semibold tracking-[0.18em] uppercase text-[#6E8577]">' + theme.label + '</p>' +
                        '<p class="toast-message text-[13px] font-medium text-[#33413A] leading-snug mt-1"></p>' +
                    '</div>' +
                    '<button type="button" data-toast-close class="flex-shrink-0 w-7 h-7 rounded-full text-[#C9A9B4] hover:text-[#33413A] hover:bg-[#F1F0EA] flex items-center justify-center transition-colors">' +
                        '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>' +
                    '</button>' +
                    '<span class="toast-progress absolute bottom-0 left-0 right-0 h-[3px] ' + theme.bar + ' opacity-60" style="animation-duration: ' + theme.duration + 'ms"></span>';

                el.querySelector('.toast-message').textContent = message;
                container.appendChild(el);
                wire(el, theme.duration);

                return el;
            }
        };

        Array.prototype.slice.call(document.querySelectorAll('[data-toast]')).forEach(function(el) {
            var duration = parseInt(el.dataset.duration, 10) || 5000;
            var stagger = parseInt(el.dataset.stagger, 10) || 0;

            wire(el, duration + stagger);
        });
    })();
</script>
@endonce
