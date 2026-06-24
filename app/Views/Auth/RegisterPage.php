<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<style>
    html {
        overflow-y: auto;
    }

    body.AuthPage {
        overflow-y: auto !important;
        background: #eef2f6;
    }

    body.AuthPage .AmbientBackground {
        display: none;
    }

    body.AuthPage .DeviceWrap {
        min-height: 100dvh;
        height: auto;
        padding: 0;
    }

    body.AuthPage .MobileFrame {
        min-height: 100dvh;
        height: auto;
        display: flex;
        flex-direction: column;
        background: #ffffff;
        overflow: visible !important;
    }

    body.AuthPage .MobileShell {
        flex: 0 0 auto;
        min-height: 100dvh;
        padding: 0;
        display: flex;
        flex-direction: column;
        overflow: visible !important;
        -webkit-overflow-scrolling: touch;
        overscroll-behavior-y: contain;
    }

    body.AuthPage .MobileShell > .FlashMessage {
        margin: 10px 12px 0;
        position: relative;
        z-index: 4;
    }

    @media (min-width: 431px) {
        body.AuthPage .DeviceWrap {
            padding: 14px 0;
        }

        body.AuthPage .MobileFrame {
            min-height: calc(100vh - 28px);
            border-radius: 28px;
            box-shadow: 0 24px 56px rgba(12, 23, 43, 0.18);
        }
    }

    .RegisterInlineView {
        flex: 0 0 auto;
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        overflow: visible;
        background: #ffffff;
    }

    .RegisterInlineHero {
        position: relative;
        padding: max(28px, calc(env(safe-area-inset-top) + 18px)) 24px 94px;
        background: linear-gradient(145deg, var(--BrandSecondary) 0%, #2b68cf 56%, var(--BrandPrimary) 100%);
        overflow: hidden;
    }

    .RegisterInlineHero::before,
    .RegisterInlineHero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .RegisterInlineHero::before {
        width: 210px;
        height: 210px;
        top: -86px;
        right: -54px;
    }

    .RegisterInlineHero::after {
        width: 150px;
        height: 150px;
        left: -62px;
        bottom: 30px;
    }

    .RegisterInlineBrand {
        position: relative;
        z-index: 1;
        display: grid;
        justify-items: center;
        gap: 10px;
        text-align: center;
        color: #fff;
    }

    .RegisterInlineLogo {
        width: 72px;
        height: 72px;
        padding: 10px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 16px 36px rgba(12, 23, 43, 0.18);
    }

    .RegisterInlineBrand strong {
        font-size: 1.8rem;
        letter-spacing: -0.03em;
    }

    .RegisterInlineBrand span {
        max-width: 220px;
        font-size: 0.8rem;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.88);
    }

    .RegisterInlineWave {
        position: absolute;
        left: -6%;
        right: -6%;
        bottom: -1px;
        height: 86px;
        background: #ffffff;
        border-top-left-radius: 58% 100%;
        border-top-right-radius: 42% 100%;
    }

    /*.RegisterInlineWave::before {*/
    /*    content: "";*/
    /*    position: absolute;*/
    /*    top: -7px;*/
    /*    left: 26%;*/
    /*    width: 34%;*/
    /*    height: 18px;*/
    /*    border-bottom-left-radius: 999px;*/
    /*    border-bottom-right-radius: 999px;*/
    /*    background: linear-gradient(135deg, #4e61ff 0%, var(--BrandPrimary) 100%);*/
    /*    transform: rotate(-2deg);*/
    /*}*/

    .RegisterInlinePanel {
        margin-top: -18px;
        padding: 24px 22px max(18px, calc(env(safe-area-inset-bottom) + 12px));
        background: #ffffff;
        border-radius: 64px 64px 0 0;
    }

    .RegisterInlineHeading {
        display: grid;
        justify-items: center;
        gap: 6px;
        margin-bottom: 12px;
        text-align: center;
    }

    .RegisterInlineHeading h2 {
        margin: 0;
        font-size: 1.02rem;
        color: var(--TextStrong);
    }

    .RegisterInlineHeading p {
        color: #8a93a4;
        font-size: 0.72rem;
    }

    .RegisterInlineNote {
        margin: 0 0 14px;
        text-align: center;
        color: #7f8796;
        font-size: 0.66rem;
        line-height: 1.55;
    }

    .RegisterInlineForm {
        display: grid;
        gap: 10px;
    }

    .RegisterInlineField {
        display: grid;
        gap: 8px;
    }

    .RegisterInlineField span {
        font-size: 0.72rem;
        font-weight: 700;
        color: #243246;
    }

    .RegisterInlineField input {
        min-height: 42px;
        padding: 10px 16px;
        border-radius: 999px;
        border: 1px solid rgba(215, 221, 231, 0.92);
        background: #f7f4f6;
        box-shadow: inset 0 1px 2px rgba(12, 23, 43, 0.03);
    }

    .RegisterInlineField input:focus {
        border-color: rgba(23, 52, 95, 0.3);
        box-shadow: 0 0 0 4px rgba(23, 52, 95, 0.08);
        background: #fff;
    }

    .RegisterInlineButton {
        min-height: 46px;
        margin-top: 4px;
        border-radius: 999px;
        border: 0;
        background: linear-gradient(135deg, var(--BrandSecondary), var(--BrandPrimary));
        color: #fff;
        font-weight: 700;
    }

    .RegisterInlineFooter {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        margin-top: 14px;
        color: var(--TextMuted);
        font-size: 0.68rem;
    }

    .RegisterInlineFooter a {
        color: var(--BrandSecondary);
        font-weight: 700;
    }

    @media (max-width: 390px) {
        .RegisterInlineHero {
            padding: max(24px, calc(env(safe-area-inset-top) + 14px)) 18px 88px;
        }

        .RegisterInlineLogo {
            width: 68px;
            height: 68px;
        }

        .RegisterInlineBrand strong {
            font-size: 1.55rem;
        }

        .RegisterInlineBrand span {
            font-size: 0.74rem;
        }

        .RegisterInlinePanel {
            padding: 22px 18px max(14px, calc(env(safe-area-inset-bottom) + 10px));
            border-top-left-radius: 56px;
            border-top-right-radius: 56px;
        }

        .RegisterInlineNote {
            font-size: 0.64rem;
        }
    }
</style>

<section class="RegisterInlineView">
    <div class="RegisterInlineHero">
        <div class="RegisterInlineBrand">
            <img src="<?= trace_logo_url() ?>" alt="<?= esc(trace_app_name()) ?>" class="RegisterInlineLogo">
            <strong><?= esc(trace_app_name()) ?></strong>
            <span><?= esc(trace_app_tagline()) ?></span>
        </div>
        <div class="RegisterInlineWave"></div>
    </div>

    <div class="RegisterInlinePanel">
        <div class="RegisterInlineHeading">
            <h2>Create account !</h2>
            <p>Daftarkan akun Anda</p>
        </div>

        <p class="RegisterInlineNote">Akun self-register akan masuk sebagai role Supervisor / PIC / Pelaksana.</p>

        <form method="post" action="<?= base_url('register') ?>" class="RegisterInlineForm">
            <?= csrf_field() ?>
            <label class="RegisterInlineField">
                <span>Nama Lengkap</span>
                <input type="text" name="fullName" value="<?= esc(old('fullName')) ?>" required>
            </label>

            <!-- <label class="RegisterInlineField">
                <span>Email (Opsional)</span>
                <input type="email" name="email" value="<?= esc(old('email')) ?>">
            </label> -->

            <label class="RegisterInlineField">
                <span>Username</span>
                <input type="text" name="username" value="<?= esc(old('username')) ?>" required>
            </label>

            <label class="RegisterInlineField">
                <span>Nomor HP</span>
                <input type="tel" name="phone" value="<?= esc(old('phone')) ?>" placeholder="Contoh: 08 / 628 / 8" required>
            </label>

            <label class="RegisterInlineField">
                <span>Password</span>
                <input type="password" name="password" required>
            </label>

            <button type="submit" class="RegisterInlineButton">Buat Akun</button>
        </form>

        <div class="RegisterInlineFooter">
            <span>Sudah punya akun?</span>
            <a href="<?= base_url('login') ?>">Kembali ke login</a>
        </div>
    </div>
</section>
<?= $this->endSection() ?>
