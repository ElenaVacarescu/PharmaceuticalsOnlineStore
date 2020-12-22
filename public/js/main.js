
$(document).ready(function () {

    var valoareSelectata;
    var nrProduseInCos;
    var idSubcateg;
    var url = location.href;
    var urlFilename = url.substring(url.lastIndexOf('/') + 1);
    var count;
    count = 0;
    idSubcateg = 0;

    var char = urlFilename.indexOf("?");
    var urlTest = urlFilename.substring(0, char);
    if (urlTest == 'viewByIdSubcategorie') {
        //preiau id-ul subcategoriei
        idSubcateg = urlFilename.substring(urlFilename.lastIndexOf('=') + 1);
        urlFilename = 'produseSubcategorie';
    }
    urlFilename += '.php';

    $('.sortareProduse').on('change', function () {
        valoareSelectata = (this.value);
        $.ajax({
            method: "POST",
            url: "http://localhost/ecommerce/Produse/sortare",
            data: {actiune: 'sortare', name: valoareSelectata, pagina: urlFilename, idSubcateg: idSubcateg}
        })
                .done(function (result) {
                    var obj = jQuery.parseJSON(result);
                    incarcaProduse(obj);
                });

    });

    //cautare   
    $("#txtSearchProduse").keydown(function (e) {
        if (e.keyCode == 13) {
            $('input[name = inputSearchProduse]').click();
            cautaProduse();
            return false;
        }
    });

    //pagina de comenzi
    $(".comandaSelectata a").click(function () {
        var idComanda = $(this).parent().parent().attr('id');
        $.ajax({
            method: "POST",
            url: 'http://localhost/ecommerce/Cont/accesareComanda',
            data: {actiune: 'comandaAccesata', idComanda: idComanda}
        })
                .done(function (result) {
                    var obj = jQuery.parseJSON(result);
                });
    });
    //accesare inactiva, cand utilizatorul iese din pagina unde avem tabelul de comenzi
    function handler(e) {
        if (($(e.target).closest("#content").attr("id") != "content")) {
            if (!$(this).data("run-once")) {
                accesareInactiva();
               $(this).data("run-once", true);
            }

        }
    }
    $(document).click(handler);

    function accesareInactiva() {
        $.ajax({
            method: "POST",
            url: 'http://localhost/ecommerce/Cont/accesareInactiva',
            data: {actiune: 'accesareInactiva'}
        });
        return;
    }

    function cautaProduse() {

        var textCautat = $('input[name = inputSearchProduse]').val();
        $.ajax({
            method: "POST",
            url: "http://localhost/ecommerce/Produse/cautare",
            data: {actiune: 'cautare', name: textCautat, pagina: 'toateProdusele.php'}
        })
                .done(function (result) {
                    var obj = jQuery.parseJSON(result);
                    incarcaProduse(obj);

                });
    }

    function incarcaProduse(data) {
        $(".continutRaspunsAjax").empty();

        for (var i = 0, keys = Object.keys(data.produseSortate), l = keys.length; i < l; i++) {
            var id = data.produseSortate[i].id;
            var denumire = data.produseSortate[i].product_title;
            var pret = data.produseSortate[i].product_price;
            var img = data.produseSortate[i].product_image;
            var path = '../public/img/' + img + '.jpg';
            var discount = data.produseSortate[i].product_discount;
            var pretFinal = Math.round((pret * (1 - discount / 100)));
            var pathDetalii = 'http://localhost/ecommerce/Produse/detaliiProdus?idProd=' + id;

            var infoProdus = $('<div>', {
                class: 'col-sm-6 col-lg-4 text-center item mb-4'
            });

            var span = $('<span>', {
                class: 'tag'
            })
            var h3 = $('<h3>', {
                class: 'text-dark'
            });

            var pPrice = $('<p>', {
                class: 'price'
            });

            $(infoProdus).appendTo(".continutRaspunsAjax");

            if (discount > 0) {
                var spanDiscount = $(span)
                        .text("Sale");
                $(infoProdus).append($(spanDiscount));
            }

            var aDetaliiProd = $("<a>")
                    .attr("href", pathDetalii);
            $(infoProdus).append($(aDetaliiProd));

            var imgDetaliiProd = $("<img>")
                    .attr("src", path)
                    .attr("alt", 'Image');
            $(aDetaliiProd).append($(imgDetaliiProd));

            var aDenumireProd = $("<a>")
                    .attr("href", pathDetalii)
                    .text(denumire);
            $(infoProdus).append($(h3));
            $(h3).append($(aDenumireProd));

            $(infoProdus).append($(pPrice));
            if (discount > 0) {
                var delTag = document.createElement("del");
                $(delTag).text(pret + ' ');
                var strongTag = document.createElement("strong");
                $(strongTag).text(pretFinal + ' lei');

                $(pPrice).append($(delTag));
                $(pPrice).append($(strongTag));

            } else {
                $(pPrice).text(pret + ' lei');
            }


        }
    }

});


