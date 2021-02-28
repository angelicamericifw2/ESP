function JarakWaktu(waktuMulai,waktuAkhir,jenis){
    var delta = Math.abs(new Date(waktuAkhir) - new Date(waktuMulai)) / 1000;

    var days = Math.floor(delta / 86400);
    delta -= days * 86400;

    var hours = Math.floor(delta / 3600) % 24;
    delta -= hours * 3600;

    var minutes = Math.floor(delta / 60) % 60;
    delta -= minutes * 60;

    var seconds = delta % 60;

    if(jenis == "jam_menit"){
        return "<b>"+hours + " JAM " + minutes +" MENIT </b>";
    } else if (jenis == "jam_menit_detik") {
        return "<b>"+hours + " JAM " + minutes +" MENIT " + seconds+ " DETIK "+"</b>";
    }
}

function isAlphabet(str) {
  return /^[a-zA-Z()]+$/.test(str);
}




function FormatHarga(value){
    return String(value).replace(/(.)(?=(\d{3})+$)/g,'$1,')
}

function InfiniteScroll(liLastItemIndex,ulClass,element,jumlahItemPerLoad){
    var allowInfinite = true;
    var lastItemIndex = $$(liLastItemIndex).length;
    var maxItems = element.length;
    var itemsPerLoad = jumlahItemPerLoad;
    if(maxItems<=jumlahItemPerLoad){
        myApp.infiniteScroll.destroy('.infinite-scroll-content');
        $$('.infinite-scroll-preloader').remove();
        return;
    }

    $$('.infinite-scroll-content').on('infinite', function () {
        if (!allowInfinite) return;

        allowInfinite = false;

        setTimeout(function () {
            allowInfinite = true;

            if (lastItemIndex >= maxItems) {
                myApp.infiniteScroll.destroy('.infinite-scroll-content');
                $$('.infinite-scroll-preloader').remove();
                return;
            }


            var html = '';
            for (var i = lastItemIndex; i <= lastItemIndex + itemsPerLoad; i++) {
                if(element[i]){
                    html += element[i];
                }
            }

            $$(ulClass).append(html);

            lastItemIndex = $$(liLastItemIndex).length;
            if (lastItemIndex >= maxItems) {
                myApp.infiniteScroll.destroy('.infinite-scroll-content');
                $$('.infinite-scroll-preloader').remove();
                return;
            }
        }, 1000);
    });

    if(maxItems <= lastItemIndex){
        myApp.infiniteScroll.destroy('.infinite-scroll-content');
        $$('.infinite-scroll-preloader').remove();
        return;
    }
}

