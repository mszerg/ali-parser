/**
 * Created by mszerg on 23.04.16.
 */

$(document).ready(function(){

    $('[id*=but1_]').click(function(){
        var id=$(this).attr('id');
        var prefix=id.substring(id.indexOf("_")+1);
        var vm_id_tovar = $('#id_tovar_'+prefix).val();
        var id_tovar_order = $('#id_tovar_order_'+prefix).val();
        var u_count = $('#count_'+prefix).val();
        $.ajax({
            type:"POST",
            url:"/tovarlist/vm_count_update/",
            data:"vm_id_tovar=" + vm_id_tovar + "&u_count=" + u_count + "&id_tovar_order="+id_tovar_order,
            success:function(result){
                $('#par1_'+prefix).html(result)
                $('#'+id).fadeOut(1000);
                $('#tovar_'+prefix + ' #chk_oprih_vm').attr("checked", true);
                /*alert('#'+id + ' Код товра='+ vm_id_tovar + ' Кол-во товара=' + u_count)*/},
            error: function(){
                alert('error')
            }
        });
        return false;
    });
    });

// Кнопка обновить количество
$(document).ready(function(){

    $('[id*=but2_]').click(function(){
        var id=$(this).attr('id');
        var prefix=id.substring(id.indexOf("_")+1);
        var id_tovar_order = $('#id_tovar_order_'+prefix).val();
        var u_count = $('#count_'+prefix).val();
        $.ajax({
            type:"POST",
            url:"/tovarlist/count_update/",
            data:"id_tovar_order=" + id_tovar_order + "&u_count=" + u_count,
            success:function(result){
                /*alert('#'+id + ' Код товра='+ $('#sum_'+prefix).html() + ' Кол-во товара=' + $('#count_partiy_'+prefix).html() + ' Произведение' + $('#sum_'+prefix).html()*$('#count_partiy_'+prefix).html()/u_count)*/
                $('#price_'+prefix).html(number_format($('#sum_'+prefix).html()*$('#count_partiy_'+prefix).html()/u_count, 2, '.', ' '))
                /*$('#par1_'+prefix).html(result)
                 alert('#'+id + ' Код товра='+ id_tovar_order + ' Кол-во товара=' + u_count)*/},
            error: function(){
                alert('error')
            }
        });
        return false;
    });
    });

// Кнопка обновить статус
$(document).ready(function(){

    $('[id*=but3_]').click(function(){
        var id=$(this).attr('id');
        var prefix=id.substring(id.indexOf("_")+1);
        var find_order = $('#tovar_'+prefix + ' #find_order').val();
        var id_ali = $('#tovar_'+prefix + ' #id_ali').val();
        $.ajax({
            type:"POST",
            url:"/tovarlist/load_status/",
            data:"find_order=" + find_order + "&id_ali=" + id_ali,
            success:function(result){
                $('#tovar_'+prefix + ' #par3').html(result)
                /*$('#'+id).fadeOut(1000);
                 $('#tovar_'+prefix + ' #chk_oprih_vm').attr("checked", true);
                 alert('#'+id + ' find_order='+ find_order + ' id_ali=' + id_ali)*/},
            error: function(){
                alert('error')
            }
        });
        return false;
    });
    });

// Кнопка обновить статус по всем заказам
$(document).ready(function(){

    $('#Refresh_All_Status').click(function(){
        $.ajax({
            url:"/tovarlist/update_all_status/",
            success:function(result){
                $('#par4').html(result)
                /*$('#'+id).fadeOut(1000);
                 $('#tovar_'+prefix + ' #chk_oprih_vm').attr("checked", true);
                 alert(result)*/},
            error: function(){
                alert('error')
            }
        });
        return false;
    });
    });

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
    prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
    sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
    dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
    s = '',
    toFixedFix = function(n, prec) {
    var k = Math.pow(10, prec);
    return '' + (Math.round(n * k) / k)
    .toFixed(prec);
    };
    // Fix for IE parseFloat(0.55).toFixed(0) = 0;
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n))
    .split('.');
    if (s[0].length > 3) {
    s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '')
    .length < prec) {
    s[1] = s[1] || '';
    s[1] += new Array(prec - s[1].length + 1)
    .join('0');
    }
    return s.join(dec);
    }


