### Genel Bakış
Projemiz 'Premier League' tahmin similasyon uygulamasıdır. 4 takım 38 hafta boyunca arasında maçlar yapmaktadır. Bu maçların sonucunda her bir takımın gücü belirlenmektedir; <br>

PTS: Toplam kazanılan maça göre belirlenen skor <br >
P: Oynanan tüm maç sayısı <br >
W: kazanılan tüm maç sayısı <br>
L: Kaybedilen tüm maç sayısı <br>
D: Takımın berabere kaldığı maç sayısı <br >
GD: Gol farkı. <br>

Tukarıda belirtilen maddeler takımın her hafta oynadığı maçlar sonucu ortaya çıkmıştır. Yapılan maçlar sonucu takımların haftalık olarak kazanma yüzdesi hesaplanmaktadır.

### Dökümantasyon
#### Kurulum
Proje sıkıştırılmış dosya olarak gönderilmektedir. Dosyadan çıkart diyerek projeyi laravel projelerinizi sakladığınız 'workspace'in içine koyabilirsiniz.

##### Back-End Kurulum
* Localinizde mysql veritabanınıza insider adında bir vertabanı oluşturun.
* Veritabanında tabloların oluşması için `php artisan migrate` komutunu terminalinizde çalıştırmanız gerekmektedir.
* Projemizde takımlar, tabloda hazır bir şekilde **gelmemektedir**. Endişelenmeye gerek yok :) sizin için takımları hazırladım. `php artisan db:seed` komutunu terminalinizde çalıştırmanız yeterli olacaktır, bu komut sizin için 4 takımı hazırlayacaktır.
* Ve son adım olarak `php artisan serve` komutunu terminal üzerinde çalıştırdığınızda api'nız çalışır duruma gelecektir.

##### Front-End Kurulum

Projemiz front end tarafında VueJs kullanmaktadır. Bu yüzden sadece `npm run dev` komutunuzu terminal üzerinde çalıştırmanız yeterli olacaktır.

##### Postman 
Projede yazılı olan tüm rotaları postman aracılığı ile kurup incelemeniz için, projenin root yolundan `doc/Inside.postman_collection.json` dosyasını bıraktım. Dosyayı postman de import etmeniz yeterli olacaktır.

#### Swagger
Projede swager desteği bulunmaktadır. Tüm rotalar için bir arayüz sağlayıcısı olan Open API destekli swagger arayüzünü kurulumunu ve rota kodlarını sisteme dahil ettim. Swagger sağlayıcısına `api/documentation` adresi üzerinden gidip incelemede bulunabilirsiniz. 

---

### Açıklama
##### Takımlar
Takımlar laravelin bize sağladığı database seeder ile hazır bir şekilde 4 takım olacak şekilde hazırlanmıştır.

##### Oynanan maçlar 
Proje 'Premier League' bir similasyon uygulaması olduğu için 38 hafta boyunca maç yapılmaktadır. her hafta için 2'ye 2 olacak şekilde takımları karşılaştırıyoruz, karşılaşan takımlar karşılıklı maç yapmaktadır, maç sonu alınan skor, o haftanın bilgisi olacak şekilde tabloya eklenmektedir. Lig sonu oynan tüm maçlar sonucunda takımın gücü hesaplanmaktadır. Takımın gücü 'Premier League' kurallarına göre hesaplanmaktadır.<br>

Kazanılan her maç için 3 puan, beraber olan takımlar için de 1 puan ve yenilen kişi ise 0 puan almaktadır.<br>


#### Haftalık lig tahminleri
Her hafta kullanıcıya göstermek için 4 takımdan kazanma tüzdesi belirlenmelidir. bu belirlenme takımın 4 takımdan en çok gol atan ve gol farkı an fazla olanı birinci yapar ve bu birinci %60 kazanma ihtimali sunar. 2.ci olan takım ise %20 ihtimal alır, 3. takım ise %15 ihtimal alır ve 4. takım ise %5 itimal alır.
