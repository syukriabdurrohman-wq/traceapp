<?= $this->extend('Layouts/MobileLayout') ?>

<?= $this->section('content') ?>
<style>
    body.AuthPage {
        overflow: hidden;
        background: #eef2f6;
    }

    body.AuthPage .AmbientBackground {
        display: none;
    }

    body.AuthPage .DeviceWrap {
        min-height: 100dvh;
        padding: 0;
    }

    body.AuthPage .MobileFrame {
        min-height: 100dvh;
        display: flex;
        flex-direction: column;
        background: #ffffff;
    }

    body.AuthPage .MobileShell {
        flex: 1;
        min-height: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
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

    .LoginInlineView {
        flex: 1;
        min-height: 0;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: #ffffff;
    }

    .LoginInlineHero {
        position: relative;
        padding: max(30px, calc(env(safe-area-inset-top) + 18px)) 24px 96px;
        background: linear-gradient(145deg, var(--BrandSecondary) 0%, #2b68cf 56%, var(--BrandPrimary) 100%);
        overflow: hidden;
    }

    .LoginInlineHero::before,
    .LoginInlineHero::after {
        content: "";
        position: absolute;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.12);
    }

    .LoginInlineHero::before {
        width: 210px;
        height: 210px;
        top: -86px;
        right: -54px;
    }

    .LoginInlineHero::after {
        width: 150px;
        height: 150px;
        left: -62px;
        bottom: 34px;
    }

    .LoginInlineBrand {
        position: relative;
        z-index: 1;
        display: grid;
        justify-items: center;
        gap: 10px;
        text-align: center;
        color: #fff;
    }

    .LoginInlineLogo {
        width: 72px;
        height: 72px;
        padding: 10px;
        border-radius: 24px;
        background: rgba(255, 255, 255, 0.98);
        box-shadow: 0 16px 36px rgba(12, 23, 43, 0.18);
    }

    .LoginInlineBrand strong {
        font-size: 1.8rem;
        letter-spacing: -0.03em;
    }

    .LoginInlineBrand span {
        max-width: 220px;
        font-size: 0.8rem;
        line-height: 1.55;
        color: rgba(255, 255, 255, 0.88);
    }

    .LoginInlineWave {
        position: absolute;
        left: -6%;
        right: -6%;
        bottom: -1px;
        height: 86px;
        background: #ffffff;
        border-top-left-radius: 58% 100%;
        border-top-right-radius: 42% 100%;
    }

    /*.LoginInlineWave::before {*/
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

    .LoginInlinePanel {
        flex: 1;
        min-height: 0;
        margin-top: -18px;
        padding: 24px 22px max(16px, calc(env(safe-area-inset-bottom) + 12px));
        display: flex;
        flex-direction: column;
        background: #ffffff;
        border-radius: 64px 64px 0 0;
    }

    .LoginInlineHeading {
        display: grid;
        justify-items: center;
        gap: 6px;
        margin-bottom: 18px;
        text-align: center;
    }

    .LoginInlineHeading h2 {
        margin: 0;
        font-size: 1.05rem;
        color: var(--TextStrong);
    }

    .LoginInlineHeading p {
        color: #8a93a4;
        font-size: 0.72rem;
    }

    .LoginInlineForm {
        display: grid;
        gap: 12px;
    }

    .LoginInlineField {
        display: grid;
        gap: 8px;
    }

    .LoginInlineField span {
        font-size: 0.72rem;
        font-weight: 700;
        color: #243246;
    }

    .LoginInlineField input {
        min-height: 44px;
        padding: 10px 16px;
        border-radius: 999px;
        border: 1px solid rgba(215, 221, 231, 0.92);
        background: #f7f4f6;
        box-shadow: inset 0 1px 2px rgba(12, 23, 43, 0.03);
    }

    .LoginInlineField input:focus {
        border-color: rgba(23, 52, 95, 0.3);
        box-shadow: 0 0 0 4px rgba(23, 52, 95, 0.08);
        background: #fff;
    }

    .LoginInlineButton {
        min-height: 46px;
        margin-top: 4px;
        border-radius: 999px;
        border: 1.5px solid rgba(23, 52, 95, 0.9);
        background: #ffffff;
        color: var(--BrandSecondary);
        font-weight: 700;
    }

    .LoginInlineDivider {
        position: relative;
        margin: auto 0 10px;
        padding-top: 16px;
        text-align: center;
    }

    .LoginInlineDivider::before {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        top: calc(50% + 8px);
        border-top: 1px solid rgba(215, 221, 231, 0.92);
    }

    .LoginInlineDivider span {
        position: relative;
        z-index: 1;
        display: inline-block;
        padding: 0 12px;
        background: #fff;
        color: var(--TextMuted);
        font-size: 0.62rem;
        font-weight: 700;
        letter-spacing: 0.08em;
    }

    .LoginInlineFooter {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        color: var(--TextMuted);
        font-size: 0.68rem;
    }

    .LoginInlineFooter a {
        color: var(--BrandSecondary);
        font-weight: 700;
    }

    @media (max-width: 390px) {
        .LoginInlineHero {
            padding: max(24px, calc(env(safe-area-inset-top) + 14px)) 18px 88px;
        }

        .LoginInlineLogo {
            width: 68px;
            height: 68px;
        }

        .LoginInlineBrand strong {
            font-size: 1.55rem;
        }

        .LoginInlineBrand span {
            font-size: 0.74rem;
        }

        .LoginInlinePanel {
            padding: 22px 18px max(14px, calc(env(safe-area-inset-bottom) + 10px));
            border-top-left-radius: 56px;
            border-top-right-radius: 56px;
        }
    }
</style>

<section class="LoginInlineView">
    <div class="LoginInlineHero">
        <div class="LoginInlineBrand">
            <img src="<?= trace_logo_url() ?>" alt="<?= esc(trace_app_name()) ?>" class="LoginInlineLogo">
            <strong><?= esc(trace_app_name()) ?></strong>
            <span><?= esc(trace_app_tagline()) ?></span>
        </div>
        <br>
        <div class="LoginInlineWave"></div>
    </div>

<div class="LoginInlinePanel">
    <div class="LoginInlineHeading">
        <h2><?= ! empty($otpMode) ? 'Verifikasi OTP' : 'Welcome back !' ?></h2>
        <p><?= ! empty($otpMode) ? 'Masukkan kode dari WhatsApp' : 'Masuk ke akun Anda' ?></p>
    </div>

    <form method="post" action="<?= ! empty($otpMode) ? base_url('login/otp') : base_url('login') ?>" class="LoginInlineForm">
        <?= csrf_field() ?>

        <?php if (! empty($otpMode)) : ?>
        <label class="LoginInlineField">
            <span>Kode OTP</span>
            <input type="text" name="otp" value="<?= esc(old('otp')) ?>" inputmode="numeric" maxlength="6" placeholder="6 digit OTP" required>
        </label>

        <button type="submit" class="LoginInlineButton">Verifikasi</button>

        <div class="LoginInlineFooter">
            <a href="<?= base_url('login') ?>">Login ulang</a>
        </div>
        <?php else : ?>
        <label class="LoginInlineField">
            <span>Nomor HP</span>
            <input type="tel" name="phone" value="<?= esc(old('phone')) ?>" placeholder="Contoh: 08 / 628 / 8" required>
        </label>

        <label class="LoginInlineField">
            <span>Password</span>
            <input type="password" name="password" placeholder="Masukkan password" required>
        </label>

        <button type="submit" class="LoginInlineButton">Login</button>

        <!-- LANGSUNG TEPAT DI BAWAH BUTTON -->
        <div class="LoginInlineDivider">
            <span>OR</span>
        </div>

        <div class="LoginInlineFooter">
            <span>Belum punya akun?</span>
            <a href="<?= base_url('register') ?>">Sign Up</a>
        </div>
        <?php endif; ?>

    </form>
</div>
</section>
<?= $this->endSection() ?>
