@props([
    'steps' => [],
    'storageKey' => 'suryatama_tutorial_seen',
])

@if(count($steps))
    @php $componentId = 'tutorial-'.uniqid(); @endphp

    <div
        id="{{ $componentId }}"
        class="fixed inset-0 z-[100] hidden"
        data-steps='@json($steps)'
        data-storage-key="{{ $storageKey }}"
    >
        <div class="tutorial-overlay absolute inset-0 bg-black/40"></div>

        <div class="tutorial-highlight absolute rounded-xl ring-4 ring-[var(--amber,#F0A202)] pointer-events-none transition-all duration-300 ease-out"></div>

        <div class="tutorial-bubble absolute w-[280px] bg-white border border-[var(--line,#DCD9CE)] rounded-2xl p-5 shadow-xl transition-all duration-300 ease-out">
            <p class="tutorial-progress text-[11px] font-mono text-[var(--ink-soft,#4B5A67)] mb-2"></p>
            <p class="tutorial-title font-semibold text-sm mb-1.5 text-[var(--ink,#16202B)]"></p>
            <p class="tutorial-text text-[var(--ink-soft,#4B5A67)] text-sm leading-relaxed mb-4"></p>
            <div class="flex items-center justify-between">
                <button type="button" class="tutorial-skip text-xs font-semibold text-[var(--ink-soft,#4B5A67)] hover:text-[var(--ink,#16202B)]">
                    Lewati
                </button>
                <button type="button" class="tutorial-next text-xs font-semibold px-4 py-2 rounded-full text-white" style="background: var(--ink,#16202B)">
                    Lanjut
                </button>
            </div>
        </div>
    </div>

    @once
        @push('scripts')
        <script>
            function initSuryatamaTutorial(root) {
                var steps = JSON.parse(root.dataset.steps);
                var storageKey = root.dataset.storageKey;
                var current = 0;

                if (localStorage.getItem(storageKey) === '1') return;
                if (!steps.length) return;

                var highlight  = root.querySelector('.tutorial-highlight');
                var bubble     = root.querySelector('.tutorial-bubble');
                var progressEl = root.querySelector('.tutorial-progress');
                var titleEl    = root.querySelector('.tutorial-title');
                var textEl     = root.querySelector('.tutorial-text');
                var nextBtn    = root.querySelector('.tutorial-next');
                var skipBtn    = root.querySelector('.tutorial-skip');

                function place() {
                    var step = steps[current];
                    var target = step.target ? document.querySelector(step.target) : null;

                    progressEl.textContent = (current + 1) + ' / ' + steps.length;
                    titleEl.textContent = step.title || '';
                    textEl.textContent = step.text || '';
                    nextBtn.textContent = current === steps.length - 1 ? 'Selesai' : 'Lanjut';

                    if (!target) {
                        highlight.style.display = 'none';
                        bubble.style.top = '50%';
                        bubble.style.left = '50%';
                        bubble.style.transform = 'translate(-50%, -50%)';
                        return;
                    }

                    target.scrollIntoView({ block: 'center', behavior: 'smooth' });

                    // Kasih jeda dikit biar posisi dihitung setelah scroll
                    setTimeout(function () {
                        var rect = target.getBoundingClientRect();

                        highlight.style.display = 'block';
                        highlight.style.top = (rect.top - 6) + 'px';
                        highlight.style.left = (rect.left - 6) + 'px';
                        highlight.style.width = (rect.width + 12) + 'px';
                        highlight.style.height = (rect.height + 12) + 'px';

                        var maxLeft = window.innerWidth - 300;
                        var bubbleLeft = Math.max(16, Math.min(rect.left, maxLeft));
                        var bubbleTop = rect.bottom + 14;

                        if (bubbleTop + 160 > window.innerHeight) {
                            bubbleTop = Math.max(16, rect.top - 174);
                        }

                        bubble.style.transform = 'none';
                        bubble.style.top = bubbleTop + 'px';
                        bubble.style.left = bubbleLeft + 'px';
                    }, 120);
                }

                function finish() {
                    root.classList.add('hidden');
                    localStorage.setItem(storageKey, '1');

                    fetch('{{ route('tutorial.seen') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}'
                        }
                    }).catch(function () {});
                }

                nextBtn.addEventListener('click', function () {
                    if (current === steps.length - 1) { finish(); return; }
                    current++;
                    place();
                });

                skipBtn.addEventListener('click', finish);
                window.addEventListener('resize', place);

                root.classList.remove('hidden');
                place();
            }
        </script>
        @endpush
    @endonce

    @push('scripts')
        <script>
            initSuryatamaTutorial(document.getElementById('{{ $componentId }}'));
        </script>
    @endpush
@endif
