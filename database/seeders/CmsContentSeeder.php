<?php

namespace Database\Seeders;

use App\Models\CmsContent;
use Illuminate\Database\Seeder;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            // ── NAV ──────────────────────────────────────────────────────────
            ['section' => 'nav', 'key' => 'nav_home', 'type' => 'text', 'en' => 'Home', 'id' => 'Beranda'],
            ['section' => 'nav', 'key' => 'nav_services', 'type' => 'text', 'en' => 'Services', 'id' => 'Layanan'],
            ['section' => 'nav', 'key' => 'nav_doctors', 'type' => 'text', 'en' => 'Doctors', 'id' => 'Dokter'],
            ['section' => 'nav', 'key' => 'nav_about', 'type' => 'text', 'en' => 'About', 'id' => 'Tentang'],
            ['section' => 'nav', 'key' => 'nav_contact', 'type' => 'text', 'en' => 'Contact', 'id' => 'Kontak'],
            ['section' => 'nav', 'key' => 'nav_book_cta', 'type' => 'text', 'en' => 'Book Now', 'id' => 'Buat Janji'],

            // ── HERO ─────────────────────────────────────────────────────────
            ['section' => 'hero', 'key' => 'hero_badge', 'type' => 'text', 'en' => '🏥 Trusted Healthcare Since 2010', 'id' => '🏥 Layanan Kesehatan Terpercaya Sejak 2010'],
            ['section' => 'hero', 'key' => 'hero_title_1', 'type' => 'text', 'en' => 'Your Health,', 'id' => 'Kesehatan Anda,'],
            ['section' => 'hero', 'key' => 'hero_title_2', 'type' => 'text', 'en' => 'Our Priority.', 'id' => 'Prioritas Kami.'],
            ['section' => 'hero', 'key' => 'hero_subtitle', 'type' => 'textarea', 'en' => 'Experience world-class medical care with cutting-edge technology and compassionate professionals — all in one smart clinic.', 'id' => 'Rasakan layanan medis berkelas dunia dengan teknologi terkini dan tenaga profesional yang penuh empati — semua dalam satu klinik pintar.'],
            ['section' => 'hero', 'key' => 'hero_cta_primary', 'type' => 'text', 'en' => 'Book Appointment', 'id' => 'Buat Janji Temu'],
            ['section' => 'hero', 'key' => 'hero_cta_secondary', 'type' => 'text', 'en' => 'Explore Services', 'id' => 'Lihat Layanan'],
            ['section' => 'hero', 'key' => 'hero_stat_1_num', 'type' => 'text', 'en' => '15,000+', 'id' => '15.000+'],
            ['section' => 'hero', 'key' => 'hero_stat_1_label', 'type' => 'text', 'en' => 'Patients Served', 'id' => 'Pasien Dilayani'],
            ['section' => 'hero', 'key' => 'hero_stat_2_num', 'type' => 'text', 'en' => '98%', 'id' => '98%'],
            ['section' => 'hero', 'key' => 'hero_stat_2_label', 'type' => 'text', 'en' => 'Satisfaction Rate', 'id' => 'Tingkat Kepuasan'],
            ['section' => 'hero', 'key' => 'hero_stat_3_num', 'type' => 'text', 'en' => '50+', 'id' => '50+'],
            ['section' => 'hero', 'key' => 'hero_stat_3_label', 'type' => 'text', 'en' => 'Specialist Doctors', 'id' => 'Dokter Spesialis'],

            // ── SERVICES ─────────────────────────────────────────────────────
            ['section' => 'services', 'key' => 'services_badge', 'type' => 'text', 'en' => 'What We Offer', 'id' => 'Yang Kami Tawarkan'],
            ['section' => 'services', 'key' => 'services_title', 'type' => 'text', 'en' => 'Comprehensive Medical Services', 'id' => 'Layanan Medis Komprehensif'],
            ['section' => 'services', 'key' => 'services_subtitle', 'type' => 'textarea', 'en' => 'From routine checkups to specialized treatments, we cover every aspect of your health journey.', 'id' => 'Dari pemeriksaan rutin hingga perawatan spesialis, kami melayani setiap aspek perjalanan kesehatan Anda.'],
            ['section' => 'services', 'key' => 'svc_1_title', 'type' => 'text', 'en' => 'General Consultation', 'id' => 'Konsultasi Umum'],
            ['section' => 'services', 'key' => 'svc_1_desc', 'type' => 'textarea', 'en' => 'Comprehensive health assessments and personalized treatment plans from experienced general practitioners.', 'id' => 'Penilaian kesehatan menyeluruh dan rencana perawatan personal dari dokter umum berpengalaman.'],
            ['section' => 'services', 'key' => 'svc_2_title', 'type' => 'text', 'en' => 'Dental Care', 'id' => 'Perawatan Gigi'],
            ['section' => 'services', 'key' => 'svc_2_desc', 'type' => 'textarea', 'en' => 'Advanced dental treatments including cosmetic dentistry, orthodontics, and preventive care.', 'id' => 'Perawatan gigi canggih termasuk estetika gigi, ortodontik, dan perawatan preventif.'],
            ['section' => 'services', 'key' => 'svc_3_title', 'type' => 'text', 'en' => 'Dermatology', 'id' => 'Dermatologi'],
            ['section' => 'services', 'key' => 'svc_3_desc', 'type' => 'textarea', 'en' => 'Expert skin care, aesthetic treatments, and medical dermatology for all skin types and conditions.', 'id' => 'Perawatan kulit ahli, perawatan estetika, dan dermatologi medis untuk semua jenis kulit.'],
            ['section' => 'services', 'key' => 'svc_4_title', 'type' => 'text', 'en' => 'Cardiology', 'id' => 'Kardiologi'],
            ['section' => 'services', 'key' => 'svc_4_desc', 'type' => 'textarea', 'en' => 'Comprehensive heart health services with state-of-the-art diagnostic equipment and specialist care.', 'id' => 'Layanan kesehatan jantung komprehensif dengan peralatan diagnostik mutakhir dan perawatan spesialis.'],
            ['section' => 'services', 'key' => 'svc_5_title', 'type' => 'text', 'en' => 'Pediatrics', 'id' => 'Pediatri'],
            ['section' => 'services', 'key' => 'svc_5_desc', 'type' => 'textarea', 'en' => 'Dedicated child healthcare from newborns to adolescents, ensuring healthy growth and development.', 'id' => 'Layanan kesehatan anak khusus dari bayi baru lahir hingga remaja, memastikan tumbuh kembang yang sehat.'],
            ['section' => 'services', 'key' => 'svc_6_title', 'type' => 'text', 'en' => 'Smart Automation', 'id' => 'Otomasi Cerdas'],
            ['section' => 'services', 'key' => 'svc_6_desc', 'type' => 'textarea', 'en' => 'AI-powered appointment reminders, follow-up scheduling, and real-time health monitoring via WhatsApp.', 'id' => 'Pengingat janji berbasis AI, penjadwalan tindak lanjut, dan pemantauan kesehatan real-time via WhatsApp.'],

            // ── WHY US ───────────────────────────────────────────────────────
            ['section' => 'why', 'key' => 'why_badge', 'type' => 'text', 'en' => 'Why Choose Us', 'id' => 'Mengapa Memilih Kami'],
            ['section' => 'why', 'key' => 'why_title', 'type' => 'text', 'en' => 'Healthcare Reimagined for the Digital Age', 'id' => 'Layanan Kesehatan yang Didesain Ulang untuk Era Digital'],
            ['section' => 'why', 'key' => 'why_subtitle', 'type' => 'textarea', 'en' => 'We combine medical excellence with smart technology to deliver an unmatched patient experience.', 'id' => 'Kami menggabungkan keunggulan medis dengan teknologi cerdas untuk memberikan pengalaman pasien yang tak tertandingi.'],
            ['section' => 'why', 'key' => 'why_1_title', 'type' => 'text', 'en' => 'Smart Booking System', 'id' => 'Sistem Pemesanan Cerdas'],
            ['section' => 'why', 'key' => 'why_1_desc', 'type' => 'textarea', 'en' => 'Book appointments in minutes with real-time slot availability. Automated reminders ensure you never miss a visit.', 'id' => 'Buat janji dalam hitungan menit dengan ketersediaan slot real-time. Pengingat otomatis memastikan Anda tidak pernah melewatkan kunjungan.'],
            ['section' => 'why', 'key' => 'why_2_title', 'type' => 'text', 'en' => 'Expert Medical Team', 'id' => 'Tim Medis Ahli'],
            ['section' => 'why', 'key' => 'why_2_desc', 'type' => 'textarea', 'en' => 'Our team of 50+ certified specialists brings decades of combined experience across all medical disciplines.', 'id' => 'Tim 50+ spesialis bersertifikat kami membawa pengalaman gabungan puluhan tahun di semua disiplin medis.'],
            ['section' => 'why', 'key' => 'why_3_title', 'type' => 'text', 'en' => 'Follow-Up Automation', 'id' => 'Otomasi Tindak Lanjut'],
            ['section' => 'why', 'key' => 'why_3_desc', 'type' => 'textarea', 'en' => 'Post-treatment follow-ups are automatically scheduled and sent via WhatsApp to ensure your full recovery.', 'id' => 'Tindak lanjut pasca perawatan dijadwalkan dan dikirim otomatis via WhatsApp untuk memastikan pemulihan penuh Anda.'],
            ['section' => 'why', 'key' => 'why_4_title', 'type' => 'text', 'en' => 'Transparent Pricing', 'id' => 'Harga Transparan'],
            ['section' => 'why', 'key' => 'why_4_desc', 'type' => 'textarea', 'en' => 'No hidden fees. Clear, upfront pricing for all services with flexible payment options available.', 'id' => 'Tidak ada biaya tersembunyi. Harga jelas dan transparan untuk semua layanan dengan opsi pembayaran fleksibel.'],

            // ── ABOUT ────────────────────────────────────────────────────────
            ['section' => 'about', 'key' => 'about_badge', 'type' => 'text', 'en' => 'Our Story', 'id' => 'Kisah Kami'],
            ['section' => 'about', 'key' => 'about_title', 'type' => 'text', 'en' => 'A Decade of Healing & Innovation', 'id' => 'Satu Dekade Penyembuhan & Inovasi'],
            ['section' => 'about', 'key' => 'about_body', 'type' => 'textarea', 'en' => 'Founded in 2010, Smart Clinic has grown from a small general practice into a comprehensive multi-specialty clinic serving thousands of patients. Our mission is simple: deliver exceptional healthcare with the warmth of a family clinic and the precision of a world-class hospital.', 'id' => 'Didirikan pada 2010, Smart Clinic telah berkembang dari praktik umum kecil menjadi klinik multi-spesialis komprehensif yang melayani ribuan pasien. Misi kami sederhana: memberikan layanan kesehatan luar biasa dengan kehangatan klinik keluarga dan ketepatan rumah sakit berkelas dunia.'],
            ['section' => 'about', 'key' => 'about_vision_title', 'type' => 'text', 'en' => 'Our Vision', 'id' => 'Visi Kami'],
            ['section' => 'about', 'key' => 'about_vision_body', 'type' => 'textarea', 'en' => 'To be the most trusted and innovative healthcare provider in the region, setting new standards in patient care and medical technology.', 'id' => 'Menjadi penyedia layanan kesehatan paling terpercaya dan inovatif di wilayah ini, menetapkan standar baru dalam perawatan pasien dan teknologi medis.'],

            // ── CTA SECTION ──────────────────────────────────────────────────
            ['section' => 'cta', 'key' => 'cta_title', 'type' => 'text', 'en' => 'Ready to Take Control of Your Health?', 'id' => 'Siap Mengambil Kendali Kesehatan Anda?'],
            ['section' => 'cta', 'key' => 'cta_subtitle', 'type' => 'textarea', 'en' => 'Book your appointment today and experience the future of healthcare. Our team is ready to help you.', 'id' => 'Buat janji hari ini dan rasakan masa depan layanan kesehatan. Tim kami siap membantu Anda.'],
            ['section' => 'cta', 'key' => 'cta_button', 'type' => 'text', 'en' => 'Book Your Appointment', 'id' => 'Buat Janji Sekarang'],
            ['section' => 'cta', 'key' => 'cta_phone', 'type' => 'text', 'en' => 'Or call us: +62 21 1234 5678', 'id' => 'Atau hubungi: +62 21 1234 5678'],

            // ── FOOTER ───────────────────────────────────────────────────────
            ['section' => 'footer', 'key' => 'footer_tagline', 'type' => 'text', 'en' => 'Your health is our mission.', 'id' => 'Kesehatan Anda adalah misi kami.'],
            ['section' => 'footer', 'key' => 'footer_address', 'type' => 'text', 'en' => 'Jl. Kesehatan No. 1, Jakarta Selatan, 12345', 'id' => 'Jl. Kesehatan No. 1, Jakarta Selatan, 12345'],
            ['section' => 'footer', 'key' => 'footer_phone', 'type' => 'text', 'en' => '+62 21 1234 5678', 'id' => '+62 21 1234 5678'],
            ['section' => 'footer', 'key' => 'footer_email', 'type' => 'text', 'en' => 'hello@smartclinic.id', 'id' => 'hello@smartclinic.id'],
            ['section' => 'footer', 'key' => 'footer_hours', 'type' => 'text', 'en' => 'Mon–Sat: 08:00–20:00 | Sun: 09:00–15:00', 'id' => 'Sen–Sab: 08:00–20:00 | Min: 09:00–15:00'],
            ['section' => 'footer', 'key' => 'footer_copy', 'type' => 'text', 'en' => '© 2025 Smart Clinic. All rights reserved.', 'id' => '© 2025 Smart Clinic. Hak cipta dilindungi.'],
        ];

        foreach ($contents as $item) {
            foreach (['en', 'id'] as $locale) {
                CmsContent::updateOrCreate(
                    ['key' => $item['key'], 'locale' => $locale],
                    [
                        'section' => $item['section'],
                        'value' => $item[$locale],
                        'type' => $item['type'],
                    ]
                );
            }
        }
    }
}
