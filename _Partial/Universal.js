//Notification First Time
$('#MenampilkanBelNotifikasi').load('_Partial/ReloadBelNotification.php');
$('#MenampilkanBelNotifikasiPesan').load('_Partial/ReloadBelNotificationPesan.php');

//Reload Notification
$(document).ready(function() {
    function ReloadBelNotification() {
        $('#MenampilkanBelNotifikasi').load('_Partial/ReloadBelNotification.php');
    }
    function ReloadBelNotificationPesan() {
        $('#MenampilkanBelNotifikasiPesan').load('_Partial/ReloadBelNotificationPesan.php');
    }
    // setInterval(ReloadBelNotification, 5000);
    setInterval(ReloadBelNotificationPesan, 5000);
});

//Kondisi Ketika Uraian Notifikasi Di Klik
$('#MenampilkanBelNotifikasi').click(function(){
    $('#MenampilkanNotificationList').html('<li class="dropdown-header">Loading...</li>');
    $('#MenampilkanNotificationList').load('_Partial/NotificationList.php');
});

//Kondisi Ketika Uraian Notifikasi Pesan Di Klik
$('#MenampilkanBelNotifikasiPesan').click(function(){
    $('#MenampilkanListNotifikasiPesan').html('<li class="dropdown-header">Loading...</li>');
    $('#MenampilkanListNotifikasiPesan').load('_Partial/NotificationListPesan.php');
});