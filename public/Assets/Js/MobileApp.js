(function () {
    let deferredInstallPrompt = null;
    let installPromptDismissed = false;
    let aosInitialized = false;

    const body = document.body;
    const pageClass = () => (body?.dataset.pageClass || '').trim();

    const registerServiceWorker = async () => {
        if (!('serviceWorker' in navigator)) {
            return;
        }

        try {
            await navigator.serviceWorker.register(window.baseUrl + 'service-worker.js?v=' + window.appAssetVersion);

            if (navigator.serviceWorker.controller) {
                navigator.serviceWorker.controller.postMessage({
                    type: 'APP_VERSION',
                    version: window.appAssetVersion,
                });
            }
        } catch (error) {
            console.warn('Service worker gagal didaftarkan', error);
        }
    };

    const shouldUseAos = () => ['AuthPage', 'HomePage'].includes(pageClass());

    const initAos = () => {
        if (!shouldUseAos() || !window.AOS) {
            return;
        }

        const selectorsByPage = {
            AuthPage: ['.AuthBrand', '.GlassCard', '.AuthFooterCard'],
            HomePage: ['.WelcomeBanner', '.CompactStatGrid', '.InfoCard', '.CarouselSection'],
        };

        (selectorsByPage[pageClass()] || []).forEach((selector) => {
            document.querySelectorAll(selector).forEach((element, index) => {
                if (!element.hasAttribute('data-aos')) {
                    element.setAttribute('data-aos', 'fade-up');
                    element.setAttribute('data-aos-delay', String(Math.min(index * 70, 220)));
                }
            });
        });

        if (!aosInitialized) {
            window.AOS.init({
                duration: 520,
                once: true,
                offset: 10,
                easing: 'ease-out-cubic',
            });
            aosInitialized = true;
            return;
        }

        window.AOS.refreshHard();
    };

    const updateInstallPromptVisibility = (visible) => {
        const prompt = document.getElementById('PwaInstallPrompt');

        if (!prompt) {
            return;
        }

        prompt.hidden = !visible;
        prompt.classList.toggle('isVisible', visible);
        prompt.style.display = visible ? 'flex' : 'none';
    };

    const isStandaloneMode = () =>
        window.matchMedia('(display-mode: standalone)').matches || window.navigator.standalone === true;

    const isMobileViewport = () => window.matchMedia('(max-width: 820px)').matches;

    const showManualInstallHint = () => {
        const isIos = /iphone|ipad|ipod/i.test(window.navigator.userAgent);
        const message = isIos
            ? 'Untuk install TRACE di iPhone/iPad, buka menu Share lalu pilih Add to Home Screen.'
            : 'Untuk install TRACE, buka menu browser lalu pilih Install App atau Tambahkan ke layar utama.';

        window.alert(message);
    };

    const initInstallPrompt = () => {
        const prompt = document.getElementById('PwaInstallPrompt');
        const installButton = document.getElementById('InstallAppButton');
        const dismissButton = document.getElementById('DismissInstallPrompt');
        const dismissedKey = 'trace-install-dismissed';
        const installedKey = 'trace-installed';

        if (!prompt || !installButton || !dismissButton) {
            return;
        }

        if (isStandaloneMode()) {
            window.localStorage.setItem(installedKey, '1');
            updateInstallPromptVisibility(false);
            return;
        }

        installPromptDismissed = window.localStorage.getItem(dismissedKey) === '1';
        const isInstalled = window.localStorage.getItem(installedKey) === '1';

        if (isInstalled) {
            updateInstallPromptVisibility(false);
            return;
        }

        const maybeShowPrompt = () => {
            if (!installPromptDismissed && !isStandaloneMode() && isMobileViewport()) {
                updateInstallPromptVisibility(true);
            }
        };

        window.addEventListener('beforeinstallprompt', (event) => {
            event.preventDefault();
            deferredInstallPrompt = event;
            maybeShowPrompt();
        });

        installButton.addEventListener('click', async () => {
            if (!deferredInstallPrompt) {
                showManualInstallHint();
                return;
            }

            deferredInstallPrompt.prompt();
            const choice = await deferredInstallPrompt.userChoice;
            deferredInstallPrompt = null;

            if (choice?.outcome === 'accepted') {
                window.localStorage.setItem(installedKey, '1');
            }

            updateInstallPromptVisibility(false);
        });

        dismissButton.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            installPromptDismissed = true;
            window.localStorage.setItem(dismissedKey, '1');
            deferredInstallPrompt = null;
            updateInstallPromptVisibility(false);
            prompt.remove();
        });

        window.addEventListener('appinstalled', () => {
            deferredInstallPrompt = null;
            window.localStorage.setItem(installedKey, '1');
            window.localStorage.removeItem(dismissedKey);
            installPromptDismissed = true;
            updateInstallPromptVisibility(false);
        });

        window.setTimeout(maybeShowPrompt, 1200);
    };

    const initPhotoPreview = () => {
        const input = document.getElementById('PhotoInput');
        const preview = document.getElementById('PhotoPreview');

        if (!input || !preview) {
            return;
        }

        input.addEventListener('change', () => {
            preview.innerHTML = '';
            Array.from(input.files || []).forEach((file) => {
                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = file.name;
                preview.appendChild(image);
            });
        });
    };

    const initCopyButtons = () => {
        document.querySelectorAll('[data-copy-target]').forEach((button) => {
            button.addEventListener('click', async () => {
                const targetId = button.getAttribute('data-copy-target');
                const target = document.getElementById(targetId);

                if (!target) {
                    return;
                }

                const defaultLabel = button.getAttribute('data-copy-default-label') || 'Salin';
                const successLabel = button.getAttribute('data-copy-success-label') || 'Tersalin';

                try {
                    await navigator.clipboard.writeText(target.textContent || '');
                    button.classList.add('CopySuccess');
                    button.setAttribute('aria-label', successLabel);
                    button.setAttribute('title', successLabel);

                    window.setTimeout(() => {
                        button.classList.remove('CopySuccess');
                        button.setAttribute('aria-label', defaultLabel);
                        button.setAttribute('title', defaultLabel);
                    }, 1600);
                } catch (error) {
                    console.warn('Gagal copy teks', error);
                }
            });
        });
    };

    const initPdfShareButtons = () => {
        document.querySelectorAll('[data-share-pdf-url]').forEach((button) => {
            button.addEventListener('click', async () => {
                const url = button.getAttribute('data-share-pdf-url');
                const fileName = button.getAttribute('data-share-pdf-name') || 'laporan.pdf';

                if (!url) {
                    return;
                }

                try {
                    const response = await fetch(url, { credentials: 'same-origin' });
                    const blob = await response.blob();
                    const file = new File([blob], fileName, { type: 'application/pdf' });

                    if (navigator.canShare?.({ files: [file] }) && navigator.share) {
                        await navigator.share({
                            files: [file],
                            title: fileName,
                            text: 'Laporan format PDF',
                        });
                        return;
                    }
                } catch (error) {
                    console.warn('Gagal membagikan PDF', error);
                }

                window.location.href = url;
            });
        });
    };

    const initOvertimeToggle = () => {
        const toggle = document.getElementById('OvertimeToggle');
        const fields = document.getElementById('OvertimeFields');

        if (!toggle || !fields) {
            return;
        }

        const sync = () => {
            fields.style.display = toggle.value === '1' ? 'grid' : 'none';
        };

        toggle.addEventListener('change', sync);
        sync();
    };

    const initConditionalCurrentLocation = () => {
        const select = document.getElementById('CurrentLocationSelect');
        const field = document.getElementById('CurrentLocationManualField');
        const structureLocationField = document.getElementById('StructureLocationField');
        const structureLocationSelect = document.getElementById('StructureLocationSelect');
        const structurePointField = document.getElementById('StructurePointField');
        const structurePointInput = document.getElementById('StructurePointInput');

        if (!select || !field) {
            return;
        }

        const input = field.querySelector('input');
        const sync = () => {
            const visible = select.value === 'Lainnya';
            const structureVisible = select.value === 'Area Laut';
            field.style.display = visible ? 'grid' : 'none';
            if (input) {
                input.disabled = !visible;
                input.required = visible;
                if (!visible) {
                    input.value = '';
                }
            }

            if (structureLocationField && structureLocationSelect) {
                structureLocationField.style.display = structureVisible ? 'grid' : 'none';
                structureLocationSelect.disabled = !structureVisible;
                structureLocationSelect.required = structureVisible;
                if (!structureVisible) {
                    structureLocationSelect.value = '';
                }
            }

            if (structurePointField && structurePointInput) {
                structurePointField.style.display = structureVisible ? 'grid' : 'none';
                structurePointInput.disabled = !structureVisible;
                structurePointInput.required = structureVisible;
                if (!structureVisible) {
                    structurePointInput.value = '';
                }
            }
        };

        select.addEventListener('change', sync);
        sync();
    };

    const initDynamicRows = () => {
        document.querySelectorAll('[data-dynamic-rows]').forEach((container) => {
            const addButton = container.querySelector('[data-add-row]');

            const reindex = () => {
                Array.from(container.querySelectorAll('[data-dynamic-row]')).forEach((row, index) => {
                    row.querySelectorAll('[name]').forEach((field) => {
                        field.name = field.name.replace(/\[\d+\]/, `[${index}]`);
                    });
                });
            };

            container.addEventListener('click', (event) => {
                const target = event.target instanceof Element ? event.target : null;
                if (!target) {
                    return;
                }

                const removeButton = target.closest('[data-remove-row]');
                if (removeButton) {
                    const rows = container.querySelectorAll('[data-dynamic-row]');
                    const row = removeButton.closest('[data-dynamic-row]');
                    if (row && rows.length > 1) {
                        row.remove();
                        reindex();
                    }
                    return;
                }

                if (target.closest('[data-add-row]') && addButton) {
                    const rows = container.querySelectorAll('[data-dynamic-row]');
                    const lastRow = rows[rows.length - 1];
                    if (!lastRow) {
                        return;
                    }

                    const clone = lastRow.cloneNode(true);
                    clone.querySelectorAll('input, textarea, select').forEach((field) => {
                        if (field instanceof HTMLInputElement || field instanceof HTMLTextAreaElement) {
                            field.value = field.name.endsWith('[unit]') ? 'unit' : '';
                        } else if (field instanceof HTMLSelectElement) {
                            field.selectedIndex = 0;
                        }
                    });
                    container.insertBefore(clone, addButton);
                    reindex();
                    container.dispatchEvent(new CustomEvent('dynamic-row-added', { bubbles: true }));
                }
            });

            reindex();
        });
    };

    const initRealizationRows = () => {
        const container = document.querySelector('[data-dynamic-rows="realizationItems"]');

        if (!container) {
            return;
        }

        const readNumber = (value) => {
            const match = String(value || '').replace(/\s+/g, '').match(/-?\d+(?:[.,]\d+)?/);

            if (!match) {
                return null;
            }

            const parsed = Number(match[0].replace(',', '.'));
            return Number.isFinite(parsed) ? parsed : null;
        };

        const formatNumber = (value) => {
            if (!Number.isFinite(value)) {
                return '';
            }

            return String(Number(value.toFixed(4))).replace('.', ',');
        };

        const syncDeviation = (row) => {
            const planInput = row.querySelector('[data-plan-input]');
            const realizationInput = row.querySelector('[data-realization-input]');
            const deviationInput = row.querySelector('[data-deviation-input]');

            if (!planInput || !realizationInput || !deviationInput) {
                return;
            }

            const plan = readNumber(planInput.value);
            const realization = readNumber(realizationInput.value);
            deviationInput.value = plan === null || realization === null ? '' : formatNumber(realization - plan);
        };

        const syncPartner = (row) => {
            const select = row.querySelector('[data-partner-select]');
            const manualField = row.querySelector('[data-partner-manual-field]');
            const manualInput = row.querySelector('[data-partner-manual-input]');

            if (!select || !manualField || !manualInput) {
                return;
            }

            const isManual = select.value === 'Lainnya';
            manualField.style.display = isManual ? 'grid' : 'none';
            manualInput.disabled = !isManual;
            manualInput.required = isManual;

            if (!isManual) {
                manualInput.value = '';
            }
        };

        const syncWorkItem = (row) => {
            const select = row.querySelector('[data-work-item-select]');
            const manualField = row.querySelector('[data-work-item-manual-field]');
            const manualInput = row.querySelector('[data-work-item-manual-input]');

            if (!select || !manualField || !manualInput) {
                return;
            }

            const isManual = select.value === 'Lainnya';
            manualField.style.display = isManual ? 'grid' : 'none';
            manualInput.disabled = !isManual;
            manualInput.required = isManual;

            if (!isManual) {
                manualInput.value = '';
            }
        };

        const syncRow = (row) => {
            syncDeviation(row);
            syncWorkItem(row);
            syncPartner(row);
        };

        const syncAll = () => {
            container.querySelectorAll('[data-dynamic-row]').forEach((row) => syncRow(row));
        };

        container.addEventListener('input', (event) => {
            const target = event.target instanceof Element ? event.target : null;
            const row = target?.closest('[data-dynamic-row]');

            if (row && (target.matches('[data-plan-input]') || target.matches('[data-realization-input]'))) {
                syncDeviation(row);
            }
        });

        container.addEventListener('change', (event) => {
            const target = event.target instanceof Element ? event.target : null;
            const row = target?.closest('[data-dynamic-row]');

            if (row && target.matches('[data-partner-select]')) {
                syncPartner(row);
            }

            if (row && target.matches('[data-work-item-select]')) {
                syncWorkItem(row);
            }
        });

        container.addEventListener('dynamic-row-added', syncAll);
        document.addEventListener('report-draft-restored', syncAll);
        syncAll();
    };

    const initHeavyEquipmentFields = () => {
        const syncField = (select) => {
            const wrapper = select.closest('.BoxedCounterField');
            const manualInput = wrapper?.querySelector('[data-heavy-manual]');

            if (!manualInput) {
                return;
            }

            const isManual = select.value === 'Lainnya';
            manualInput.style.display = isManual ? 'block' : 'none';
            manualInput.disabled = !isManual;
            manualInput.required = isManual;

            if (!isManual) {
                manualInput.value = '';
            }
        };

        const syncAll = () => {
            document.querySelectorAll('[data-heavy-select]').forEach((select) => syncField(select));
        };

        document.addEventListener('change', (event) => {
            const target = event.target instanceof Element ? event.target : null;

            if (target?.matches('[data-heavy-select]')) {
                syncField(target);
            }
        });

        document.addEventListener('report-draft-restored', syncAll);
        syncAll();
    };

    const initLightToolFields = () => {
        const syncField = (select) => {
            const wrapper = select.closest('.BoxedCounterField');
            const manualInput = wrapper?.querySelector('[data-light-manual]');

            if (!manualInput) {
                return;
            }

            const isManual = select.value === 'Lainnya';
            manualInput.style.display = isManual ? 'block' : 'none';
            manualInput.disabled = !isManual;
            manualInput.required = isManual;

            if (!isManual) {
                manualInput.value = '';
            }
        };

        const syncAll = () => {
            document.querySelectorAll('[data-light-select]').forEach((select) => syncField(select));
        };

        document.addEventListener('change', (event) => {
            const target = event.target instanceof Element ? event.target : null;

            if (target?.matches('[data-light-select]')) {
                syncField(target);
            }
        });

        document.addEventListener('report-draft-restored', syncAll);
        syncAll();
    };

    const initBannerCarousel = () => {
        const carousel = document.getElementById('BannerCarousel');
        const dotsWrap = document.getElementById('CarouselDots');

        if (!carousel || !dotsWrap) {
            return;
        }

        const slides = Array.from(carousel.children);
        const dots = Array.from(dotsWrap.children);

        if (slides.length === 0 || dots.length === 0) {
            return;
        }

        const getActiveIndex = () => {
            let nearestIndex = 0;
            let nearestDistance = Number.POSITIVE_INFINITY;

            slides.forEach((slide, index) => {
                const distance = Math.abs(carousel.scrollLeft - slide.offsetLeft);
                if (distance < nearestDistance) {
                    nearestDistance = distance;
                    nearestIndex = index;
                }
            });

            return nearestIndex;
        };

        const syncDots = () => {
            const index = getActiveIndex();
            dots.forEach((dot, dotIndex) => {
                dot.classList.toggle('isActive', dotIndex === index);
            });
        };

        let activeIndex = 0;
        window.setInterval(() => {
            activeIndex = (activeIndex + 1) % slides.length;
            carousel.scrollTo({
                left: slides[activeIndex].offsetLeft,
                behavior: 'smooth',
            });
            syncDots();
        }, 3500);

        carousel.addEventListener('scroll', syncDots, { passive: true });
        syncDots();
    };

    const initQuickMenuToggle = () => {
        const toggle = document.getElementById('QuickMenuToggle');
        const extra = document.getElementById('QuickMenuExtra');

        if (!toggle || !extra) {
            return;
        }

        toggle.addEventListener('click', () => {
            const expanded = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', expanded ? 'false' : 'true');
            extra.hidden = expanded;
            toggle.classList.toggle('isActive', !expanded);
        });
    };

    const initStatusToggle = () => {
        const button = document.getElementById('StatusToggleButton');

        if (!button) {
            return;
        }

        const inputId = button.getAttribute('data-target-input');
        const input = inputId ? document.getElementById(inputId) : null;
        const activeValue = button.getAttribute('data-status-active') || 'Active';
        const inactiveValue = button.getAttribute('data-status-inactive') || 'Inactive';

        if (!(input instanceof HTMLInputElement)) {
            return;
        }

        const sync = (isActive) => {
            input.value = isActive ? activeValue : inactiveValue;
            button.classList.toggle('isActive', isActive);
            button.classList.toggle('isInactive', !isActive);
            button.setAttribute('aria-pressed', isActive ? 'true' : 'false');
        };

        sync(input.value === activeValue);

        button.addEventListener('click', () => {
            sync(input.value !== activeValue);
        });
    };

    const initAutoSendWaToggle = () => {
        const toggles = Array.from(document.querySelectorAll('[data-auto-wa-toggle]'));
        const hiddenInputs = Array.from(document.querySelectorAll('[data-auto-wa-input]'));

        if (toggles.length === 0 && hiddenInputs.length === 0) {
            return;
        }

        const storageKey = 'trace-auto-send-wa';

        const sync = (enabled) => {
            toggles.forEach((toggle) => {
                if (toggle instanceof HTMLInputElement) {
                    toggle.checked = enabled;
                }
            });

            hiddenInputs.forEach((input) => {
                if (input instanceof HTMLInputElement) {
                    input.value = enabled ? '1' : '0';
                }
            });

            window.localStorage.setItem(storageKey, enabled ? '1' : '0');
        };

        const initialEnabled = window.localStorage.getItem(storageKey) === '1';

        toggles.forEach((toggle) => {
            toggle.addEventListener('change', () => {
                sync(toggle instanceof HTMLInputElement ? toggle.checked : false);
            });
        });

        sync(initialEnabled);
    };

    const initReportWizard = () => {
        const form = document.getElementById('ReportWizardForm');
        if (!form) {
            return;
        }

        const steps = Array.from(form.querySelectorAll('.WizardStep'));
        const chips = Array.from(form.querySelectorAll('.WizardChip'));
        const currentStepInput = document.getElementById('CurrentStepInput');
        const draftPrompt = document.getElementById('ReportDraftPrompt');
        const saveDraftButton = draftPrompt?.querySelector('[data-draft-save-exit]');
        const stayButton = draftPrompt?.querySelector('[data-draft-stay]');
        const draftKey = form.getAttribute('data-draft-key') || 'trace-report-draft:new';
        const shell = form.closest('.MobileShell');
        let currentStep = Number(form.dataset.step || currentStepInput?.value || 1);
        const totalSteps = steps.length;
        let allowExit = false;
        let isSubmitting = false;
        let pendingExitAction = null;
        let autoSaveTimer = 0;
        let allowHistoryBack = false;
        const trackedHiddenFields = new Set(['reportId', 'currentStep']);
        const baselineSnapshot = JSON.stringify(collectFormState());

        function collectFormState() {
            const snapshot = {};
            const elements = Array.from(form.elements);

            elements.forEach((field) => {
                if (!field.name || field.disabled) {
                    return;
                }

                if (
                    !(
                        field instanceof HTMLInputElement
                        || field instanceof HTMLTextAreaElement
                        || field instanceof HTMLSelectElement
                    )
                ) {
                    return;
                }

                if (['file', 'submit', 'button', 'reset'].includes(field.type)) {
                    return;
                }

                if (field.type === 'hidden' && !trackedHiddenFields.has(field.name)) {
                    return;
                }

                if (field.type === 'radio') {
                    if (field.checked) {
                        snapshot[field.name] = field.value;
                    }

                    return;
                }

                if (field.type === 'checkbox') {
                    snapshot[field.name] = field.checked ? field.value || '1' : '';
                    return;
                }

                snapshot[field.name] = field.value;
            });

            return snapshot;
        }

        const saveDraftSnapshot = () => {
            try {
                const snapshot = collectFormState();
                snapshot.__currentStep = String(currentStep);
                window.localStorage.setItem(
                    draftKey,
                    JSON.stringify({
                        savedAt: Date.now(),
                        data: snapshot,
                    }),
                );
            } catch (error) {
                console.warn('Gagal menyimpan auto draft lokal', error);
            }
        };

        const restoreDraftSnapshot = () => {
            const raw = window.localStorage.getItem(draftKey);

            if (!raw) {
                return;
            }

            try {
                const parsed = JSON.parse(raw);
                const snapshot = parsed?.data ?? parsed;

                Object.entries(snapshot).forEach(([name, value]) => {
                    if (name === '__currentStep') {
                        currentStep = Number(value || currentStep);
                        return;
                    }

                    const fields = Array.from(form.elements).filter(
                        (field) => 'name' in field && field.name === name,
                    );

                    fields.forEach((field) => {
                        if (
                            !(
                                field instanceof HTMLInputElement
                                || field instanceof HTMLTextAreaElement
                                || field instanceof HTMLSelectElement
                            )
                        ) {
                            return;
                        }

                        if (field.type === 'radio') {
                            field.checked = field.value === String(value);
                            return;
                        }

                        if (field.type === 'checkbox') {
                            field.checked = value === field.value || value === '1' || value === true;
                            return;
                        }

                        if (field.type !== 'file') {
                            field.value = String(value ?? '');
                        }
                    });
                });
                form.querySelectorAll('select').forEach((select) => {
                    select.dispatchEvent(new Event('change', { bubbles: true }));
                });
                document.dispatchEvent(new CustomEvent('report-draft-restored'));
            } catch (error) {
                console.warn('Gagal memulihkan auto draft lokal', error);
            }
        };

        const hasPendingFiles = () =>
            Array.from(form.querySelectorAll('input[type="file"]')).some((input) => (input.files?.length ?? 0) > 0);

        const shouldPromptBeforeExit = () => {
            if (allowExit || isSubmitting) {
                return false;
            }

            return JSON.stringify(collectFormState()) !== baselineSnapshot || hasPendingFiles();
        };

        const scrollToTop = () => {
            if (shell instanceof HTMLElement) {
                shell.scrollTo({ top: 0, behavior: 'smooth' });
                return;
            }

            window.scrollTo({ top: 0, behavior: 'smooth' });
        };

        const resolveHashTarget = () => {
            const hash = window.location.hash;
            if (!hash || hash === '#') {
                return null;
            }

            const target = form.querySelector(hash);
            if (!(target instanceof HTMLElement)) {
                return null;
            }

            const step = target.closest('.WizardStep');
            if (!(step instanceof HTMLElement)) {
                return null;
            }

            return {
                target,
                stepNumber: Number(step.getAttribute('data-wizard-step') || currentStep),
            };
        };

        const syncStep = (focusTarget = null) => {
            currentStep = Math.max(1, Math.min(totalSteps, currentStep));

            steps.forEach((step) => {
                const stepNumber = Number(step.getAttribute('data-wizard-step'));
                step.style.display = stepNumber === currentStep ? 'block' : 'none';
            });

            chips.forEach((chip) => {
                const stepNumber = Number(chip.getAttribute('data-wizard-jump'));
                chip.classList.toggle('isActive', stepNumber === currentStep);
            });

            if (currentStepInput) {
                currentStepInput.value = String(currentStep);
            }

            if (focusTarget instanceof HTMLElement) {
                window.setTimeout(() => {
                    focusTarget.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start',
                        inline: 'nearest',
                    });
                }, 80);
                return;
            }

            scrollToTop();
        };

        const closeDraftPrompt = () => {
            if (!draftPrompt) {
                return;
            }

            draftPrompt.hidden = true;
            document.body.classList.remove('ReportDraftPromptOpen');
        };

        const openDraftPrompt = (onConfirm) => {
            if (!draftPrompt) {
                return;
            }

            pendingExitAction = onConfirm;
            draftPrompt.hidden = false;
            document.body.classList.add('ReportDraftPromptOpen');
        };

        const scheduleAutoSave = () => {
            window.clearTimeout(autoSaveTimer);
            autoSaveTimer = window.setTimeout(() => {
                saveDraftSnapshot();
            }, 500);
        };

        restoreDraftSnapshot();

        const initialHashTarget = resolveHashTarget();
        if (initialHashTarget !== null) {
            currentStep = initialHashTarget.stepNumber;
        }

        if (saveDraftButton) {
            saveDraftButton.addEventListener('click', () => {
                saveDraftSnapshot();
                closeDraftPrompt();
                const nextAction = pendingExitAction;
                pendingExitAction = null;
                allowExit = true;
                if (typeof nextAction === 'function') {
                    nextAction();
                }
            });
        }

        if (stayButton) {
            stayButton.addEventListener('click', () => {
                pendingExitAction = null;
                closeDraftPrompt();
            });
        }

        draftPrompt?.addEventListener('click', (event) => {
            if (event.target === draftPrompt) {
                pendingExitAction = null;
                closeDraftPrompt();
            }
        });

        form.querySelectorAll('[data-wizard-next]').forEach((button) => {
            button.addEventListener('click', () => {
                currentStep += 1;
                syncStep();
            });
        });

        form.querySelectorAll('[data-wizard-prev]').forEach((button) => {
            button.addEventListener('click', () => {
                currentStep -= 1;
                syncStep();
            });
        });

        chips.forEach((chip) => {
            chip.addEventListener('click', () => {
                currentStep = Number(chip.getAttribute('data-wizard-jump'));
                syncStep();
            });
        });

        form.addEventListener('input', scheduleAutoSave);
        form.addEventListener('change', scheduleAutoSave);
        form.addEventListener('submit', () => {
            isSubmitting = true;
            allowExit = true;
            window.clearTimeout(autoSaveTimer);
            window.localStorage.removeItem(draftKey);
        });

        document.addEventListener('click', (event) => {
            const target = event.target;
            if (!(target instanceof Element)) {
                return;
            }

            const anchor = target.closest('a[href]');
            if (!(anchor instanceof HTMLAnchorElement)) {
                return;
            }

            if (
                event.defaultPrevented
                || event.metaKey
                || event.ctrlKey
                || event.shiftKey
                || event.altKey
                || anchor.target === '_blank'
                || anchor.hasAttribute('download')
            ) {
                return;
            }

            const href = anchor.getAttribute('href') || '';
            if (href === '' || href.startsWith('#')) {
                return;
            }

            const nextUrl = new URL(anchor.href, window.location.href);
            if (nextUrl.href === window.location.href || !shouldPromptBeforeExit()) {
                return;
            }

            event.preventDefault();
            openDraftPrompt(() => {
                window.location.href = nextUrl.href;
            });
        });

        window.addEventListener('beforeunload', (event) => {
            if (!shouldPromptBeforeExit()) {
                return;
            }

            window.clearTimeout(autoSaveTimer);
            saveDraftSnapshot();
            event.preventDefault();
            event.returnValue = '';
        });

        window.history.replaceState(
            Object.assign({}, window.history.state || {}, { traceDraftBase: true }),
            '',
            window.location.href,
        );
        window.history.pushState({ traceDraftGuard: true }, '', window.location.href);

        window.addEventListener('popstate', () => {
            if (allowHistoryBack || allowExit) {
                allowHistoryBack = false;
                return;
            }

            if (!shouldPromptBeforeExit()) {
                allowHistoryBack = true;
                window.history.back();
                return;
            }

            window.history.pushState({ traceDraftGuard: true }, '', window.location.href);
            openDraftPrompt(() => {
                allowHistoryBack = true;
                window.history.back();
            });
        });

        window.addEventListener('hashchange', () => {
            const hashTarget = resolveHashTarget();
            if (hashTarget === null) {
                return;
            }

            currentStep = hashTarget.stepNumber;
            syncStep(hashTarget.target);
        });

        syncStep(initialHashTarget?.target ?? null);
    };

    const initViewportAssist = () => {
        const root = document.documentElement;
        const focusSelector = 'input, textarea, select';

        const scrollFocusedFieldIntoView = () => {
            const activeElement = document.activeElement;

            if (!(activeElement instanceof HTMLElement) || !activeElement.matches(focusSelector)) {
                return;
            }

            window.setTimeout(() => {
                activeElement.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center',
                    inline: 'nearest',
                });
            }, 260);
        };

        document.addEventListener('focusin', (event) => {
            if (event.target instanceof HTMLElement && event.target.matches(focusSelector)) {
                scrollFocusedFieldIntoView();
            }
        });

        const syncViewportState = () => {
            if (!window.visualViewport) {
                return;
            }

            const keyboardHeight = window.innerHeight - window.visualViewport.height;
            const isKeyboardOpen = keyboardHeight > 140;

            document.body.classList.toggle('KeyboardOpen', isKeyboardOpen);
            root.style.setProperty('--KeyboardInset', '0px');

            if (isKeyboardOpen) {
                scrollFocusedFieldIntoView();
            }
        };

        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', syncViewportState);
            window.visualViewport.addEventListener('scroll', syncViewportState);
            syncViewportState();
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        registerServiceWorker();
        initAos();
        initInstallPrompt();
        initPhotoPreview();
        initCopyButtons();
        initPdfShareButtons();
        initOvertimeToggle();
        initConditionalCurrentLocation();
        initDynamicRows();
        initRealizationRows();
        initHeavyEquipmentFields();
        initLightToolFields();
        initBannerCarousel();
        initQuickMenuToggle();
        initStatusToggle();
        initAutoSendWaToggle();
        initReportWizard();
        initViewportAssist();
    });

    window.addEventListener('pageshow', () => {
        initAos();
        if (isStandaloneMode()) {
            window.localStorage.setItem('trace-installed', '1');
            updateInstallPromptVisibility(false);
        }
    });
})();
