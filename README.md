# Wordpress-Like
Bu eklenti, etiketlenmiş her yazının altına, tek görüntülendiği zaman bir beğeni düğmesi ekler. Beğeni düğmesinde görüntülenecek yazı ayar menüsünden kullanıcı tarafından değiştirilebilir. Varsayılan olarak tepkisiz ve beğenmeme durumlarında "Beğen", beğenme durumunda ise "Beğenildi" yazısı görüntülenir.

Eklenti çalışabilmek için wordpress veri tabanı içerisinde **Likers** ve **Liked_Tags** adlı iki adet tablo oluşturur. **Likers** tablosunda **User_Post_ID** ve **Is_Liked** olmak üzere iki sutun bulunur. **User_Post_ID** kullanıcı ve yazıların birincil anahtarları kullanılarak oluşturulmuş, türetülmiş anahtardır ve **Likers** tablosunun birincil anahtarıdır. **Is_Liked** ise kullanıcının bu yazıyıyı beğenip beğenmediğini saklar. **Liked_Tags** tablosunda ise **Term_ID** ve **Like_Count** olmak üzere yine iki sütün vardır. **Term_ID** ilgili etiketin birincil anahtarını, **Like_Count** ise o etikete sahip yazıların beğenilme sayısını gösterir.

Bir kullanıcı herhangi bir yazıyıda beğenme butonuna bastığı zaman veritabanında bulunan kayıtlara bakılarak, o kullanıcı ve yazıyla ilişkilendirilmiş girdi olup oladığına bakılır. Var ise girdi güncellenerek 1 ya da 0 olarak değiştirilir. Önemli olan sayılar olmasına rağmen girdinin silinmemesinin nedeni, beğenmeme butonu eklenmesi durumunda karşmaşıklığın oluşmasını engellemektir.

Eklenti ile beraber sunulan bileşen(widget) sayfaya eklenerek varsayılan olarak on en beğenilen etiket sayfada sıralanabilir.

Elentinin oturum açmamış kullanıcılarda çalışması planlanmamıştır. Bu yüzden beğeni butonu sadece oturum açmış kullanıcılara ve tekil sayfalarda görüntülenir.

Yönetici menüsünden butonlarda gösterilen yazılar değiştirilebilir. V Favoriler alanından her bir etiketin almış olduğu beğeni sayısı gözlenebilir.
## Kurulum
Eklenti fazlandan zahmet harcamaksızın sadece worpress eklentisi olarak eklenip kullanılabilir.
