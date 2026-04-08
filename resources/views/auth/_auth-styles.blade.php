<style>
/* Shared auth card styles (used by login/register/password views) */
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 72vh; /* keep footer clear */
    padding: 36px 18px;
    box-sizing: border-box;
}

.auth-box {
    background: #ffffff;
    padding: 36px 40px;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(20,20,30,0.08);
    width: 100%;
    max-width: 520px;
    border: 1px solid rgba(16,16,24,0.04);
}

.auth-box h2 {
    margin-bottom: 20px;
    text-align: center;
    color: #222;
    font-weight: 600;
}

.form-group { margin-bottom: 18px; }
.form-group label { display:block; margin-bottom:8px; color: #565656; font-weight:500; }
.form-group input[type="email"],
.form-group input[type="password"],
.form-group input[type="text"] {
    width: 100%;
    padding: 12px 14px;
    border-radius: 8px;
    border: 1px solid #e6e6e9;
    background: #fbfdff;
    box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    font-size: 15px;
    transition: box-shadow .12s ease, border-color .12s ease;
}
.form-group input:focus { outline: none; border-color: #ff9f1a; box-shadow: 0 6px 18px rgba(255,159,26,0.12); }

.btn { display: inline-block; cursor: pointer; border: none; }
.btn-primary { background: #FFA500; color: #fff; font-weight:700; }
.btn-primary:hover { background:#ffb233; }
.btn-inline { width: auto; min-width: 140px; padding: 12px 28px; border-radius: 8px; box-shadow: 0 10px 30px rgba(255,165,0,0.18); }

.auth-actions { display:flex; gap:18px; align-items:center; margin-top:6px; }
.auth-actions__left { margin-right: auto; }
.forgot-link { color:#6b6b6b; font-weight:500; text-decoration:none; font-size:14px; }
.forgot-link:hover, .forgot-link:focus { color:#FF8C00; text-decoration:underline; }
.forgot-note { color:#777; margin-bottom:12px; }

.auth-link { text-align:center; margin-top:20px; color:#666; }
.auth-link a { color:#FFA500; text-decoration:none; }
.alert { padding:12px; margin-bottom:18px; border-radius:8px; }
.alert-error { background:#fff4f2; color:#7a1800; border:1px solid #ffd6cc; }
.alert-success { background:#f7fff7; color:#0b6b2b; border:1px solid #d4f5d6; }

@media (max-width:480px) {
    .auth-box { padding: 26px; border-radius: 10px; }
    .auth-actions { flex-direction: column; align-items:stretch; }
    .auth-actions__left { margin-right:0; }
    .btn-inline { width:100%; }
}
</style>
