<?php
/**
 * Template Name: Contact Page
 */

get_header();
?>

<div class="contact-page" style="min-height: 100vh; background: #f9fafb; padding: 3rem 0;">
    <div class="contact-container" style="max-width: 1280px; margin: 0 auto; padding: 0 1rem;">
        <h1 style="text-align: center; font-size: 2.5rem; margin: 0 0 0.5rem; font-weight: 700;">Contact Us</h1>
        <p class="subtitle" style="text-align: center; color: #666; font-size: 1.125rem; margin: 0 0 3rem;">Get in touch with our team</p>

        <div class="contact-grid" style="display: grid; grid-template-columns: 1fr; gap: 2rem; background: white; padding: 2rem; border-radius: 0.75rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);">
            <div class="contact-info" style="padding: 1rem;">
                <h2 style="font-size: 1.5rem; margin: 0 0 1.5rem; font-weight: 600;">Get In Touch</h2>
                <div class="info-items" style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div class="info-item" style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="4" width="20" height="16" rx="2" />
                            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7" />
                        </svg>
                        <div>
                            <p style="margin: 0; font-weight: 600;">Email</p>
                            <span style="color: #666;">support@<?php echo parse_url( home_url(), PHP_URL_HOST ); ?></span>
                        </div>
                    </div>
                    <div class="info-item" style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                        </svg>
                        <div>
                            <p style="margin: 0; font-weight: 600;">Phone</p>
                            <span style="color: #666;">+1 (555) 123-4567</span>
                        </div>
                    </div>
                    <div class="info-item" style="display: flex; gap: 1rem; align-items: flex-start;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <div>
                            <p style="margin: 0; font-weight: 600;">Address</p>
                            <span style="color: #666;">123 Fashion Street, NY 10001</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-wrapper" style="padding: 1rem;">
                <h2 style="font-size: 1.5rem; margin: 0 0 1.5rem; font-weight: 600;">Send us a Message</h2>
                <?php echo do_shortcode('[contact-form-7 id="1" title="Contact Form"]'); ?>

                <!-- Fallback form if Contact Form 7 is not installed -->
                <form method="post" action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" class="contact-form" style="display: flex; flex-direction: column; gap: 1rem;">
                    <input type="hidden" name="action" value="submit_contact_form">

                    <div class="form-field">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Name</label>
                        <input type="text" name="name" placeholder="Your name" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <div class="form-field">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Email</label>
                        <input type="email" name="email" placeholder="your@email.com" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;">
                    </div>

                    <div class="form-field">
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 500;">Message</label>
                        <textarea name="message" placeholder="How can we help you?" rows="5" required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.375rem;"></textarea>
                    </div>

                    <button type="submit" style="width: 100%; padding: 0.75rem 1.5rem; background: #000; color: white; border: none; border-radius: 0.375rem; font-weight: 600; cursor: pointer; transition: background 0.2s;">
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@media (min-width: 768px) {
    .contact-grid {
        grid-template-columns: 1fr 1fr !important;
    }
}
</style>

<?php
get_footer();
