📋 **İçindekiler**

- 🌟 **Özellikler**

- 🚀 **Kurulum**

- 🎨 **Özelleştirme**

- 📁 **Dosya Yapısı**

- ⚙️ **Fonksiyonlar**

- 🔧 **Geliştirici Notları**

- 🤝 **Katkıda Bulunma**

- 📄 **Lisans**

  
🌟 **Özellikler**

✨ **Temel Özellikler**

- **Bootstrap 5 Entegrasyonu: Modern, responsive ve mobil-uyumlu yapı**

- **Canlı Arama (AJAX): Anlık sonuç gösteren gelişmiş arama sistemi**

- **Responsive Drawer Menü: Mobil cihazlar için kayar menü navigasyonu**

- **Sticky Header: Sayfa aşağı kaydırıldığında sabit kalan üst menü**

- **CSS Token Sistemi: Logo renklerine göre otomatik ayarlanan değişkenler  
**

🎨 **Tasarım ve UX**

- **Koyu/Açık Tema Desteği:** CSS değişkenleri ile esnek renk yönetimi

- **Özel Tipografi:** Inter, Poppins, Ubuntu font aileleri

- **Animasyonlu Geçişler:** Smooth scroll ve hover efektleri

- **Breadcrumb Navigasyon:** Kullanıcı dostu sayfa yolu göstergesi

- **Breaking News Ticker:** Haber kaydırma şeridi
**

⚡ **Performans**

- **Optimize Edilmiş CSS:** CSS değişkenleri ile minimal kod

- ***Lazy Load Desteği:** Görseller için performans optimizasyonu

- **Minimal JavaScript:** Sadece gerekli fonksiyonlar

- **Cache Dostu Yapı:** Statik asset yönetimi  
**

🔧 **Geliştirici Araçları**

- **Customizer Entegrasyonu:** WordPress özelleştirici panelinden ayarlar

- **Child Theme Desteği:** Kolay özelleştirme imkanı

- **Template Hierarchy:** WordPress standartlarına uygun şablon yapısı

- **Hook & Filter Sistemi:** Genişletilebilir fonksiyon mimarisi

**Tema Önizleme**
<a href="https://github.com/epiusu/pisipixhan/blob/main/Di%C4%9Fer/alvWPTema.gif?raw=true"><img src="https://github.com/epiusu/pisipixhan/blob/main/Di%C4%9Fer/alvWPTema.gif?raw=true" width="1915" height="920" class="aligncenter size-full" /></a>

🚀 **Kurulum**

Gereksinimler

*WordPress:    6.0 veya üzeri*

*PHP:          8.0 veya üzeri*

*MySQL:        5.6 veya üzeri / MariaDB 10.1+*

*Bootstrap:    5.3+ (CDN üzerinden yüklenir)*


**Manuel Kurulum**


1. **`AlvWPTema2026` klasörünü indirin**

2. **WordPress yönetim paneline gidin: Görünüm \> Temalar \> Yeni Ekle \> Tema Yükle**

3. ZIP dosyasını yükleyin ve etkinleştirin

4. **Görünüm \> Özelleştir bölümünden tema ayarlarını yapın**

**FTP ile Kurulum**

1. Tema klasörünü wp-content/themes/ dizinine yükleyin

/wp-content/themes/AlvWPTema2026/

2. WordPress admin panelinden temayı etkinleştirin


**WP-CLI ile Kurulum**


wp theme install /path/to/AlvWPTema2026 –activate


🎨 **Özelleştirme**


**CSS Değişkenleri (Tokens)**

**Tema, `style.css` içinde tanımlanan CSS custom properties ile renk ve stil yönetimi sunar:**

  
:root \{

  /\* Ana Renkler \*/

  --alv-red: \#b81c1c;           /\* Logo kırmızısı \*/

  --alv-navy: \#1c1c2e;          /\* Logo siyahı/koyu gri \*/

  

  /\* Tipografi \*/

  --alv-font-ui: 'Inter', system-ui, sans-serif;

  --alv-font-head: 'Poppins', system-ui, sans-serif;

  

  /\* Layout \*/

  --alv-container: 1200px;

  --alv-navbar-h: 68px;

  --alv-radius: 10px;

\}  
  
**Customizer Ayarları**


**Görünüm \> Özelleştir panelinden erişilebilen ayarlar:**



| 🎨 Renkler | Header, navbar, footer renkleri | `\#1a2744`, `\#c0392b` |
| - | - | - |
| 🖼️ Logo | Logo boyutları, padding, hizalama | `60px`, `center` |
| 🔍 Arama | Arama çubuğu stilleri | `\#1a1a2e`, `rgba(255,255,255,0.38)` |
| 📱 Mobil | Mobil logo ve menü ayarları | `48px`, `left` |
| 🏠 Ana Sayfa | Hero banner, kategori kartları | Başlık, alt başlık, ikonlar |

  
**functions.php ile Özelleştirme**


// Tema desteği ekleme add\_theme\_support( 'custom-logo', array( 'height' =\> 80, 'width'  =\> 260, 'flex-height' =\> true, ) );

// Menü alanları kaydetme register\_nav\_menus( array( 'primary' =\> \_\_( 'Üst Menü (Navbar)', 'altema' ), 'footer'  =\> \_\_( 'Alt Menü (Footer)', 'altema' ), 'mobile'  =\> \_\_( 'Mobil Drawer Menüsü', 'altema' ), ) );


**📁 Dosya Yapısı**

AlvWPTema2026/

─ style.css                 \# Ana stil dosyası + tema header

functions.php             \# Tema fonksiyonları ve hook'lar

─ index.php                 \# Ana şablon dosyası

─ header.php                \# Header bölümü

─ footer.php                \# Footer bölümü

─ sidebar.php               \# Sidebar (kullanılmıyor - full width)

─ single.php                \# Tekil yazı şablonu

─ page.php                  \# Sayfa şablonu

─ archive.php               \# Arşiv/kategori şablonu

─ category.php              \# Kategori özel şablonu

─ search.php                \# Arama sonuçları şablonu

─ 404.php                   \# 404 hata sayfası

─ comments.php              \# Yorumlar bölümü

─ template-ussubilgi.php    \# Özel şablon: Üssü Bilgi sayfası

│

─📁 css/                      \# Ek CSS dosyaları

│   └── editor-style.css         \# Editör stilleri

│

├── 📁 js/                       \# JavaScript dosyaları

│   └── main.js                  \# Ana JS: drawer, live search, like

│

├── 📁 inc/                      \# Dahili sınıflar ve fonksiyonlar

│   ├── class-nav-walker.php     \# Özel menu walker sınıfı

│   ├── customizer.php           \# Customizer ayarları

│   ├── admin-page.php           \# Admin panel özelleştirmeleri

│   └── template-tags.php        \# Şablon etiket fonksiyonları

│

├── 📁 category-templates/       \# Kategori özel şablonları

│

├── 📁 template-parts/           \# Parçalı şablon bileşenleri

│

├── 🖼️ screenshot.webp          \# Tema önizleme görseli

─ LICENSE                        \# GPL-2.0 Lisansı

─ README.md                 \# Bu dosya


**⚙️ Fonksiyonlar**

**AJAX Live Search**


// functions.php içinde tanımlı add\_action( 'wp\_ajax\_alv\_live\_search', 'altema\_live\_search' ); add\_action( 'wp\_ajax\_nopriv\_alv\_live\_search', 'altema\_live\_search' );

// Kullanım (frontend - main.js) fetch(alvData.ajaxUrl, \{ method: 'POST', body: new URLSearchParams(\{ action: 'alv\_live\_search', q: 'arama\_terimi' \}) \})


**Beğen (Like) Sistemi**


// AJAX ile beğeni artırma add\_action( 'wp\_ajax\_alv\_like', 'altema\_like\_handler' ); add\_action( 'wp\_ajax\_nopriv\_alv\_like', 'altema\_like\_handler' );

// IP bazlı tekrar beğenme engelleme $ip\_key = 'alv\_liked\_ips\_' . $post\_id;


**Yardımcı Fonksiyonlar**


| Fonksiyon | Açıklama | Parametreler |
| :-: | :-: | :-: |
| `alv\_option($key, $default)` | Tema ayarlarını okur | `$key`: ayar anahtarı, `$default`: varsayılan değer |
| `altema\_cat\_total($cat\_id)` | Kategori + alt kategori toplam yazı sayısı | `$cat\_id`: kategori ID |
| `altema\_breadcrumb()` | Breadcrumb HTML çıktısı üretir | Yok |
| `altema\_pagination($query)` | Sayfalama linkleri oluşturur | `$query`: WP\_Query objesi (opsiyonel) |
| `altema\_has\_sidebar()` | Sidebar var mı kontrolü | Her zaman `false` döner (full-width) |


**🔧 Geliştirici Notları**

**Child Theme Oluşturma**

**/\* style.css - Child Theme \*/**

**/\***

**Theme Name: AlvWPTema2026 Child**

**Theme URI: https://altema.com.tr/child**

**Description: AlvWPTema2026 için child tema**

**Author: Senin Adın**

**Template: AlvWPTema2026**

**Version: 1.0**

**\*/**


**Hook Kullanımı**


// Yeni bir hook ekleme do\_action( 'alv\_before\_header' ); do\_action( 'alv\_after\_content' );

// Mevcut hook'lara müdahale add\_filter( 'excerpt\_length', 'benim\_ozel\_uzunlugum', 999 ); add\_filter( 'body\_class', 'benim\_siniflarim' );


**CSS Override**


/\* style.css - Child Theme içinde \*/

:root \{

  --alv-red: \#ff0000; /\* Kırmızı rengi değiştir \*/

\}


.alv-hero \{

  background: linear-gradient(135deg, \#000, \#333); /\* Hero arka planı \*/

\}


**Çeviri (Translation)**


// functions.php define( 'ALV\_TEXT', 'altema' );

// Kullanım echo \_\_( 'Hoşgeldiniz', ALV\_TEXT );


***`.pot` *dosyası oluşturmak için:**


wp i18n make-pot . languages/altema.pot


**🤝 Katkıda Bulunma**


1. Repoyu fork edin

2. **Yeni branch oluşturun:** `git checkout -b feature/yeni-ozellik`

3. ***Değişikliklerinizi commit edin:** `git commit -am 'feat: yeni özellik eklendi'`

4. ***Branch'inizi push edin:** `git push origin feature/yeni-ozellik`

5. Pull Request oluşturun


**Kod Standartları**


- ✅ **WordPress Coding Standards'a uyum**

- ✅ **PHP 8.0+ syntax**

- ✅ ***CSS değişkenleri ile modüler stil**

- ✅ **JS: ES6+ syntax, CDN Bootstrap bağımlılığı**


**🛠️ Sorun Giderme**



| Sorun | Olası Neden | Çözüm |
| :-: | :-: | :-: |
| Canlı arama çalışmıyor | AJAX nonce hatası | `alvData.nonce` değerini kontrol edin |
| Menü görünmüyor | Menü atanmamış | **Görünüm \> Menüler**'den "Mobil Drawer Menüsü"nü atayın |
| CSS yüklenmiyor | CDN bağlantı sorunu | `functions.php` içindeki CDN URL'lerini kontrol edin |
| Like butonu hata veriyor | IP tespiti hatası | `$\_SERVER\['REMOTE\_ADDR'\]` proxy ayarlarını kontrol edin |



**📄 Lisans**

Bu tema **GNU General Public License v2.0** altında lisanslanmıştır.


Bu program özgür yazılımdır: GNU Genel Kamu Lisansı'nın 2. veya 

(daha yeni) bir sürümü altında yeniden dağıtabilir ve/veya değiştirebilirsiniz.


Bu program faydalı olması umuduyla, ancak HİÇBİR GARANTİ OLMADAN, 

SATILABİLİRLİK veya BELİRLİ BİR AMACA UYGUNLUK garantisi bile 

olmaksızın dağıtılmıştır.


**Detaylar için: [LICENSE](https://github.com/epiusu/AlvWPTema2026?tab=GPL-2.0-1-ov-file) dosyasını inceleyin.**


## **📬 İletişim ve Destek**

- **🌐 Web:** [https://github.com/epiusu/AlvWPTema2026/](https://github.com/epiusu/AlvWPTema2026/)

- **🐛 Hata Bildirimi:** [GitHub Issues**](https://github.com/epiusu/AlvWPTema2026/issues)


**⚠️ Önemli Not:** Bu tema aktif geliştirme aşamasındadır. Production ortamında kullanmadan önce test ortamında detaylı test yapmanız önerilir.

*Wordpresss temayı test ortamında deneyiniz. WordPress temalarını test ortamında denemek için en güvenli ve yaygın yöntemler Hazırlama (Staging) Sitesi oluşturma, **[WordPress Playground**](https://wordpress.org/playground/) kullanma veya Prova Sitesi kurmaktır.*


**🎯 Proje Durumu:** 🟢 Aktif Geliştirme
**📅 Son Güncelleme:** 20 Mayıs 2026
**🔖 Versiyon:** 1.25

**AlvWPTema2026 - Modern Haber Teması © 2026* 🚀**

