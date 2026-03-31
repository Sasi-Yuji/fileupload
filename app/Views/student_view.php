<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>Student Profile<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="header-row">
        <div>
            <h1 style="font-size: 1.875rem; font-weight: 800; color: #1e293b;">Student Profile</h1>
            <p style="color: #64748b; margin-top: 0.25rem;">Detailed academic and personal information for the selected student.</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="/students" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back
            </a>
            <a href="/students/edit/<?= $student['id'] ?>" class="btn btn-primary">
                <i class="fas fa-edit"></i> Edit Profile
            </a>
        </div>
    </div>

    <div class="card" style="padding: 0; overflow: hidden; border: 1px solid var(--border-color);">
        <div class="profile-grid">
            <div class="profile-sidebar">
                <div style="position: relative; display: inline-block; margin-bottom: 2rem;">
                    <img src="/uploads/profile/<?= $student['profile_photo'] ?>" style="width: 180px; height: 180px; border-radius: 20px; object-fit: cover; box-shadow: var(--shadow); border: 5px solid #fff;" alt="Profile">
                    <span style="position: absolute; bottom: 10px; right: 10px; width: 24px; height: 24px; background: #10b981; border: 3px solid #fff; border-radius: 50%;"></span>
                </div>
                <h2 style="margin-bottom: 0.5rem; color: #1e293b;"><?= esc($student['name']) ?></h2>
                <span style="background: rgba(79, 70, 229, 0.1); color: var(--primary-color); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; text-transform: uppercase;"><?= esc($student['department']) ?> Department</span>
                
                <div style="margin-top: 2.5rem; text-align: left; padding: 0 1rem;">
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Email Address</label>
                        <span style="font-size: 0.9375rem; color: var(--text-main); font-weight: 500; display: block; overflow: hidden; text-overflow: ellipsis;"><?= esc($student['email']) ?></span>
                    </div>
                    <div style="margin-bottom: 1.5rem;">
                        <label style="display: block; font-size: 0.75rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px;">Contact Number</label>
                        <span style="font-size: 0.9375rem; color: var(--text-main); font-weight: 500;"><?= esc($student['phone']) ?></span>
                    </div>
                </div>
            </div>

            <div style="padding: 3rem;">
                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                    <i class="far fa-folder-open" style="color: var(--primary-color);"></i> Primary Documents
                </h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 3rem;">
                    <a href="/uploads/resume/<?= $student['resume'] ?>" target="_blank" class="document-box">
                        <div class="doc-icon blue"><i class="far fa-file-pdf"></i></div>
                        <div>
                            <span class="doc-title">Resume (CV)</span>
                            <span class="doc-sub">Application Metadata</span>
                        </div>
                    </a>
                    <a href="/uploads/id_proof/<?= $student['id_proof'] ?>" target="_blank" class="document-box">
                        <div class="doc-icon pink"><i class="far fa-id-card"></i></div>
                        <div>
                            <span class="doc-title">Identification</span>
                            <span class="doc-sub">Verified ID Proof</span>
                        </div>
                    </a>
                </div>

                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-award" style="color: #eab308;"></i> Certificates (<?= count($certificates) ?>)
                </h3>
                <?php if(!empty($certificates)): ?>
                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 3rem;">
                        <?php foreach($certificates as $cert): ?>
                            <a href="/uploads/certificates/<?= $cert['file_name'] ?>" target="_blank" class="certificate-badge" title="<?= esc($cert['file_name']) ?>">
                                <i class="fas fa-certificate"></i>
                                <span><?= esc($cert['file_name']) ?></span>
                                <i class="fas fa-external-link-alt" style="font-size: 0.75rem; color: #cbd5e1; margin-left: auto;"></i>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="empty-state">No certificates uploaded.</div>
                <?php endif; ?>

                <h3 style="font-size: 1.25rem; font-weight: 700; color: #1e293b; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                    <i class="fas fa-file-signature" style="color: var(--primary-color);"></i> Verified Signature
                </h3>
                <div style="display: flex; gap: 2rem; align-items: flex-start; margin-bottom: 2rem;">
                    <div class="signature-box" style="flex-shrink: 0;">
                        <?php if ($student['signature']): ?>
                            <img src="/uploads/signature/<?= $student['signature'] ?>" alt="Signature">
                        <?php else: ?>
                            <p>No signature available.</p>
                        <?php endif; ?>
                    </div>
                    
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 1.25rem; border-radius: 12px; flex: 1;">
                        <h4 style="font-size: 0.8125rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: #64748b; margin-bottom: 0.75rem;"><i class="fas fa-shield-alt"></i> Industrial Security Metadata</h4>
                        <div style="font-family: 'Courier New', monospace; font-size: 0.8125rem; color: #334155; word-break: break-all; background: #fff; padding: 10px; border-radius: 6px; border: 1px solid #cbd5e1; margin-bottom: 0.75rem;">
                            <span style="color: #64748b;">Fingerprint:</span> <?= $student['digital_signature_hash'] ?? 'Legacy Record - No Hash' ?>
                        </div>
                        <div style="display: flex; gap: 10px;">
                            <span style="background: #e0f2fe; color: #0369a1; font-size: 0.7rem; font-weight: 700; padding: 4px 8px; border-radius: 4px; text-transform: uppercase;"><i class="fas fa-lock"></i> AES-256 Encrypted</span>
                            <span style="background: #f0fdf4; color: #15803d; font-size: 0.7rem; font-weight: 700; padding: 4px 8px; border-radius: 4px; text-transform: uppercase;"><i class="fas fa-history"></i> Audit Trail Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .document-box {
        text-decoration: none; padding: 1.25rem; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: flex; align-items: center; gap: 1rem; transition: all 0.2s; box-shadow: var(--shadow-sm);
    }
    .document-box:hover { border-color: var(--primary-color); background: #fcfdfe; }
    .doc-icon { width: 48px; height: 48px; min-width: 48px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
    .doc-icon.blue { background: #eff6ff; color: #3b82f6; }
    .doc-icon.pink { background: #fdf2f8; color: #db2777; }
    .doc-title { display: block; font-size: 0.9375rem; font-weight: 600; color: var(--text-main); }
    .doc-sub { font-size: 0.75rem; color: var(--text-muted); }
    .certificate-badge { text-decoration: none; padding: 0.875rem 1.25rem; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: flex; align-items: center; gap: 12px; transition: all 0.2s; box-shadow: var(--shadow-sm); min-width: 0; }
    .certificate-badge:hover { border-color: #eab308; background: #fffbeb; transform: translateY(-2px); box-shadow: var(--shadow); }
    .certificate-badge i:first-child { color: #eab308; font-size: 1.125rem; flex-shrink: 0; }
    .certificate-badge span { font-size: 0.875rem; font-weight: 600; color: #4338ca; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; flex: 1; }
    .signature-box { padding: 1.5rem; background: #fff; border: 1px solid var(--border-color); border-radius: 12px; display: inline-block; }
    .signature-box img { max-width: 250px; height: auto; display: block; border-radius: 8px; }
    .empty-state { padding: 2rem; border: 2px dashed var(--border-color); border-radius: 12px; text-align: center; color: var(--text-muted); margin-bottom: 3rem; }
</style>
<?= $this->endSection() ?>
