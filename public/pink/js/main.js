jQuery(document).ready(function($){
    $('.commentlist li').each(function (i) {
        $(this).find('div.commentNumber').text('#'+ (i+1))
    });

    $('#commentform').on('click', '#submit', function (e) {

        e.preventDefault();

        var submitBtn = $(this);
       $('.wrapper-result').css('color', 'green').text('Сохранение комментария').fadeIn(500, function () {


            var data = $('#commentform').serializeArray();

            $.ajax({
               url:$('#commentform').attr('action'),
               data: data,
               type:'POST',
               headers:{ 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
               datatype:'JSON',
               success: function (html) {
                   if(html.error){

                       $('.wrapper-result').css('color', 'red').append(' <br /> Ошибка : <br /> ' + html.error.join('<br />'));
                       $('.wrapper-result').delay(3000).fadeOut(500, function(){

                       })
                   }
                   if(html.success){
                       console.log('1');
                       $('.wrapper-result')
                           .text('Комментарий сохранен!')
                           .delay(1000)
                           .fadeOut(500, function(){
                               console.log('2');
                                if(html.data.parentId > 0){
                                    submitBtn.parents('div#respond').prev().after('<ul class = "coment">' + html.comment + '</ul>');
                                }
                                else {
                                    if($.contains('#comments', 'ol.commentlist')){
                                        $('ol.commentlist').append(html.comment);
                                    }
                                    else {
                                        $('#respond').before('<ol class="commentlist group">' + html.comment + '</ol>')
                                    }
                                }
                                $('#cancel-comment-reply-link').click();
                           })


                   }


               },
                error: function (error) {
                    $('.wrapper-result').css('color', 'red').text('<strong>Произошла ошибка</strong>');
                    $('.wrapper-result').delay(1000).fadeOut(300, function(){
                        $('#cancel-comment-reply-link').click();
                    })
                },

            });
        });
    })

})