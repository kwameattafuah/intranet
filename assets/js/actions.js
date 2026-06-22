  $(document).ready(function(){
    // Initialise Carousel
    $('#ourCarousel').carousel();     
    // Initialize collapse button
    $(".button-collapse").sideNav();
    // Initialize collapsible (uncomment the line below if you use the dropdown variation)
    $('.collapsible').collapsible();
    // dropdown
    $('.dropdown-button').dropdown({
      inDuration: 300,
      outDuration: 225,
      constrainWidth: true, // Does not change width of dropdown to that of the activator
      gutter: 0, // Spacing from edge
      belowOrigin: true, // Displays dropdown below the button
      alignment: 'right', // Displays dropdown with edge aligned to the left of button
      stopPropagation: false // Stops event propagation
    });
    $(".dropdown-right").dropdown({
      inDuration: 300,
      outDuration: 255,
      constrainWidth: false,
      belowOrigin: true,
      alignment: 'left'
    });

    // modal
    $('.modal').modal();

    $(document).on("click", "[data-toggle='modal']", function(e){
      $(".collapsible").collapsible({
        accordion: true
      });     
    });   

    $(document).on("click", ".print", function(){
        var restorepage = document.body.innerHTML;
        var printcontent = document.getElementById('aejay').innerHTML;
        document.body.innerHTML = printcontent;
        window.print();
        document.body.innerHTML = restorepage;
    });
        
    // navigation function
    function nav(){
        var url = window.location;
        var element = $('.side li a').filter(function() {
            return this.href == url || url.href.indexOf(this.href) == 0;
        }).parent().addClass('active');
        $(".brand-logo").text(($(".side li.active .topview").text() == ' Home ') ? 'GACL Intranet' : $(".side li.active .topview").text() );
        //$("title").text($(".side li.active .topview").text());
    }
    // call navigation function
    nav();    

    function adminnav(){
        $(".brand-logo").text($(".admin-menu-nav li.active").text());
        //$("title").text($(".admin-menu-nav li.active").text());
    } 

    // ldc navigation function
    function ldcnav(){
        $(".brand-logo").text($(".side-menu-nav li.active").text());
        //$("title").text($(".side-menu-nav li.active").text());
    }    

    $(document).on("click", ".side-menu", function(e){
      $('.active').removeClass('active');  
      $(this).parent().addClass('active');
        // call navigation function
        ldcnav();   
    });

    $(document).on("click", ".admin-menu", function(e){
      $('.active').removeClass('active');  
      $(this).parent().addClass('active');
        // call navigation function
        adminnav();   
    });    

     // simple ajax calls function
    function myAjax(t, n, o, type) {
        var e = '';
        if (type == 'post') {
            var id = $(t).attr("id");
            var query = $(t).attr("data-query");
            var value = $(t).attr("data-value");
            var e = 'id='+ encodeURIComponent(id) + '&query='+ encodeURIComponent(query) + '&value='+ encodeURIComponent(value);
        }else if(type == 'form' || type == 'dynamic'){
            var e = new FormData(t);
        }

        if (type == 'post') {
            $.ajax({
                type: "POST",
                url: n,
                data: e,
                dataType: "html",
                beforeSend: function() {
                    $(o).html('Processing ...');
                },
                success: function(t) {
                    $(o).html(t).show();
                }
            });
        }else if (type == 'form'){
            $.ajax({
                type: "POST",
                url: n,
                data: e,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $(o).html('Processing ...');
                },
                success: function(t) {
                    $(o).html(t).show();
                },
                error: function(t) {
                    console.log("something's wrong")
                }
            });
        }else{
            $.ajax({
                type: "POST",
                url: n,
                data: e,
                cache: false,
                contentType: false,
                processData: false,                                
                success: function(data) {
                    $(o).html(data);
                },
                error: function(data) {
                    console.log("something's wrong: " + data);
                }
            });
        }
    }

    /*realtime search function*/
    function getStates(thisInput){
      var $trigger = $(thisInput).attr("data-trigger");  
      var query = $(thisInput).attr("data-query");
      var search = $(thisInput).val();
      var destination = $(thisInput).attr("data-dest");
      var output = $(thisInput).attr("data-output");
      var table = $(thisInput).attr("data-id");

      $.post(destination, {search:search, id:query, table:table},function(data){
        $(output).html(data);
      });
    }

    function getaStates(thisInput){
      var query = $(thisInput).attr("data-query");
      var search = $(thisInput).val();
      var destination = $(thisInput).attr("data-dest");
      var output = $(thisInput).attr("data-output");
      var table = $(thisInput).attr("data-id");

      $.post(destination, {asearch:search, id:query, table:table},function(data){
        $(output).html(data);
      });
    }

    function getDuals(thisInput){
      var query = $(thisInput).attr("data-query");
      var asearch = $(thisInput).val();
      var dual = $(thisInput).attr("dual-data");
      var bsearch = $(dual).val();
      var destination = $(thisInput).attr("data-dest");
      var output = $(thisInput).attr("data-output");
      var table = $(thisInput).attr("data-id");

      $.post(destination, {asearch:asearch, bsearch:bsearch, id:query, table:table},function(data){
        $(output).html(data);
      });
    }    


    // image view before upload
    function readUrl(input,dest){
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(dest).attr('src', e.target.result);
                console.log('onload');

                var size = input.files[0].size;
                var sizeMB = Math.ceil(size/1024);

                if (sizeMB >= 3000) {
                    $('.modal-content').html('<p class="flow-text red-text center-align">Image too big, compress and re-upload</p>');
                    $('#gaclModal').modal('open');

                    $(input).val('');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $(document).on('change', '.image', function(e){
        readUrl(this,$(this).parent().find('img'));
    });

    /*specific calls and output*/
    $(document).on("click", ".spec-ajax", function(e){
        e.preventDefault();
        $output = $(this).attr("data-output");
        $dest = $(this).attr("data-dest");
        $trigger = $(this).attr("data-trigger");
        $fadeOut = $(this).attr("data-fadeOut");
        $extend = $(this).attr("data-extend-view");
        $parent = $(this).attr("data-parent");
        $checkout = $(this).attr("data-checkout");
        $return = $(this).attr("return");

        if (typeof($trigger) != 'undefined' || $trigger != null) {
            $($trigger).trigger("click");
            return;
        }

        if (typeof($extend) != 'undefined' || $extend != null) {
            $source = $(this).parents($parent).find($extend);
            $($output).html($($source).html());

            if ($(this).attr("data-toggle") == 'modal') {
                $("#gaclModal").modal("open");
            } 
        }

        if (typeof($return) != 'undefined' || $return != null)
            return true;

        myAjax(this, $dest, $output, 'post');

        if ($(this).attr("data-toggle") == 'modal') {
            $("#gaclModal").modal("open");
        } 

        if ($(this).attr("data-toggle") == 'showroom') {
            $("#showroom").modal("open");
        }         

        if(typeof($fadeOut) != 'undefined' || $fadeOut != null){
            $(this).parents($fadeOut).fadeOut("slow").html("");
        }

        if(typeof($checkout) != 'undefined' || $checkout != null){
            $('input[type=checkbox]').each(function () {
            if(this.checked)
                $(this).parents($checkout).fadeOut("slow").html("");
            });
        }

    });

    /*specific form ajax calls*/
    $(document).on("submit", ".form", function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        
        $output = $(this).attr("data-output");
        $dest = $(this).attr("data-dest");
        $checkout = $(this).attr("data-checkout");
        $type = $(this).attr("form-type");

        myAjax(this, $dest, $output, $type);

        if ($(this).attr("data-toggle") == 'modal') {
            $("#gaclModal").modal("open");
        }

        if ($(this).attr("data-clear-input") == 'true') {
            $(this).find("input,textarea").val("");
        }

        if(typeof($checkout) != 'undefined' || $checkout != null){
            $('input[type=checkbox]').each(function () {
            if(this.checked)
                $(this).parents($checkout).fadeOut("slow").html("");
            });
        }
    });

    // dynamic search initialized
    $(document).on("keyup change", ".dynamic-search", function(e){
        getStates(this);
    });    

    $(document).on("keyup change", ".admin-search", function(e){
        getaStates(this);
    });  

    $(document).on("keyup change", ".dynamic-dual", function(e){
        getDuals(this);
    });

    // checkbox trigger
    $(document).on("change", ".check", function(e){
        if(this.checked) {
            $("#delete").removeClass("hidden");
            $("#update").removeClass("hidden");
        }else{
            if(!$("input.check").is(':checked')){
                $("#delete").addClass("hidden");
                $("#update").addClass("hidden");
            }
        }
        // define variables
        $counter = 0;
        $id = '0';
        $('input[type=checkbox]').each(function () {
            if(this.checked){
                $id += "," + $(this).val();
                $counter++;
            }
        });

        // assignment
        $("#delete").attr("data-query", $id);
        $("#update").attr("data-query", $id);

        if ($counter > 0)
            $(".selected").text($counter + " item selected");
        else if ($counter === 0)
            $(".selected").text("");
        
        if ($counter > 1 || $counter == 0)
            $("#update").addClass("hidden");
        else
            $("#update").removeClass("hidden");
    });

}); 