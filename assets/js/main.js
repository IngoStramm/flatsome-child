jQuery( function( $ ) {

	var verifica_formato_cep = function(val) {
		var verifica = /^[0-9]{5}-[0-9]{3}$/;
		val = $.trim(val);
		if(verifica.test( val )) {
			return true;
		} else {
			return false;
		}
	};

	var exibe_esconde_campos_checkout = function(val, force_show, fields ) {
		if( typeof force_show !== 'undefined' && typeof fields !== 'undefined' ) {
			console.log('1');
			console.log('force_show: ' + force_show);
			console.log('fields: ' + fields);
			if( force_show && fields ) {
				console.log('1.1');
				for (var a = fields.length - 1; a >= 0; a--) {
					fields[a].addClass('visivel');
				}
			} else if( !force_show && fields ){
				console.log('1.2');
				for (var b = fields.length - 1; b >= 0; i--) {
					fields[b].removeClass('visivel');
				}
			}
		} else if(verifica_formato_cep(val)) {
			console.log('2');
			$('#billing_address_1_field, #billing_number_field, #billing_address_2_field, #billing_neighborhood_field, #billing_city_field, #billing_state_field').addClass('visivel');
		} else {
			console.log('3');
			$('#billing_address_1_field, #billing_number_field, #billing_address_2_field, #billing_neighborhood_field, #billing_city_field, #billing_state_field').removeClass('visivel');
		}

	};

	var verifica_se_tem_um_espaco = function(val){
		return val.indexOf(' ') >= 0;
	};

	var preenche_nome_sobrenome = function() {
		$('.nome-completo').each(function(){
			var nome = $(this).find('input');
			var row = nome.closest('.form-row');
			var first_name = nome.closest('form').find('.primeiro-nome').find('input');
			var last_name = nome.closest('form').find('.sobrenome').find('input');
			$(nome).keyup(function(){
				var primeiro_nome = '';
				var sobrenome = '';
				var css_class = nome.attr('class');
				val = $.trim($(this).val());
				if(verifica_se_tem_um_espaco(val)) {
					primeiro_nome = val.substr(0, val.indexOf(' '));
					sobrenome = val.substr(val.indexOf(' ') + 1);
				} else {
					primeiro_nome = val;
				}
				first_name.val($.trim(primeiro_nome));
				last_name.val($.trim(sobrenome));

				if(row.hasClass('invalido')) {
					valida_nome_completo(nome);
				}
			}); // $(nome).keyup
			nome.blur(function(){
				valida_nome_completo(nome);
			});
		});
	};

	var valida_nome_completo = function(nome){
		var row = nome.closest('.form-row');
		val = $.trim(nome.val());
		if(!verifica_se_tem_um_espaco(val)) {
			row.addClass('invalido');
		} else {
			row.removeClass('invalido');
		}
	};

	var nome_completo_init = function(){
		preenche_nome_sobrenome();
	};

	var muda_foco_para_numero = function(){
		$('#billing_postcode').each(function(){
			var cep_input = $(this);
			var cep_row = cep_input.closest('.form-row');
			var numero_row = cep_input.closest('form').find('#billing_number_field');
			var numero_input = numero_row.find('input');
			var rua_input = $('#billing_address_1');
			cep_input.blur(function(e){
				if(cep_input.val() !== '' && numero_row.is(':visible')) {
					if(rua_input.val() !== '') {
						numero_input.focus();
					} else {
						rua_input.focus();
					}
				} else if(cep_row.hasClass('woocommerce-invalid') || cep_row.hasClass('woocommerce-invalid-required-field') ) {
					e.preventDefault();
					cep_input.focus();
				}
			});
		});
	};

	var addressAutoComplete = function( field ) {
		// Checks with *_postcode field exist.
		if ( $( '#' + field + '_postcode' ).length ) {

			// Valid CEP.
			var cep       = $( '#' + field + '_postcode' ).val().replace( '.', '' ).replace( '-', '' ),
				country   = $( '#' + field + '_country' ).val(),
				address_1 = $( '#' + field + '_address_1' ).val();

			// Check country is BR.
			if ( cep !== '' && 8 === cep.length && 'BR' === country/* && 0 === address_1.length*/ ) {

				var correios = $.ajax({
					type: 'GET',
					url: '//correiosapi.apphb.com/cep/' + cep,
					dataType: 'jsonp',
					crossDomain: true,
					contentType: 'application/json'
				});

				// Gets the address.
				correios.done( function ( address ) {

					// Address.
					if ( '' !== address.tipoDeLogradouro ) {
						$( '#' + field + '_address_1' ).val( address.tipoDeLogradouro + ' ' + address.logradouro ).change();
					} else {
						$( '#' + field + '_address_1' ).val( address.logradouro ).change();
					}

					// Neighborhood.
					$( '#' + field + '_neighborhood' ).val( address.bairro ).change();

					// City.
					if( '' !== address.cidade ) {
						$( '#' + field + '_city' ).val( address.cidade ).change();
					} else {
						exibe_esconde_campos_checkout(val, true, array( $( '#' + field + '_city' ) ) );
					}

					// State.
					if( '' !== address.estado ) {
						$( '#' + field + '_state option:selected' ).attr( 'selected', false ).change();
						$( '#' + field + '_state option[value="' + address.estado + '"]' ).attr( 'selected', 'selected' ).change();
						$( '#' + field + '_state' ).val( address.estado ).change();
					} else {
						exibe_esconde_campos_checkout(val, true, array( $( '#' + field + '_state' ) ) );
					}

					// Chosen support.
					$( '#' + field + '_state' ).trigger( 'liszt:updated' ).trigger( 'chosen:updated' );

					$('#billing_number').focus();

					if( $('.cep-notice').length ) {
						$('.cep-notice').remove();
					}

				}).fail( function( jqXHR, textStatus, errorThrown ){

					if( !$('.cep-notice').length ) {

						var msg = $('<div class="cep-notice"><div class="woocommerce-messages alert-color"><div class="message-wrapper"><ul class="woocommerce-error"><li><div class="message-container container"><span class="message-icon icon-close"><strong>CEP</strong> não encontrado. Digite outro CEP ou preencha manualmente o restante das informações do endereço.</span></div></li></ul></div></div></div>');

						$('#billing_postcode').focus();
						$('#billing_postcode').closest('form.checkout.woocommerce-checkout').prepend(msg);

					}

				});
			}
		}
	};

	var addressAutoCompleteOnChange = function( field ) {
		// $( document.body ).on( 'blur', '#' + field + '_postcode', function() {
			addressAutoComplete( field );
		// });
	};

	var init_cep = function() {

		addressAutoComplete( 'billing' );

		cep_input = $('#billing_postcode');

		exibe_esconde_campos_checkout(cep_input.val());

		cep_input.keyup(function(){
			var val = $(this).val();
			exibe_esconde_campos_checkout(val);
			if(val.length === 9) {
				addressAutoComplete( 'billing' );
				$( 'body' ).trigger( 'update_checkout' );
			}
		});		
	
		$('.address-field').find('input').each(function(){
			var input = $(this);
			input.keydown(function(e){
				if(input.attr('id') !== 'billing_postcode' && !input.closest('.address-field').hasClass('visivel')) {
					e.preventDefault();
				}
			});
		});

	};

	var masks_init = function(){
		// $( '.rg-mask' ).find( 'input' ).mask( '00.000.000-0' );
		$( '.cep-mask' ).find( 'input' ).mask( '00000-000' );
		$( '.cep-mask-input' ).mask( '00000-000' );
	};

	var desativa_campos = function() {
		$('.disabled-input').each(function(){
			var input = $(this).find('input, textarea');
			if( input.length ) {
				input.attr('readonly', 'readonly');
			}
		});
	};

	var fix_layout_inscricao_estadual = function(){
		$( '#billing_persontype' ).on( 'change', function () {
			var current = $( this ).val();
			if( $('#billing_ie').length > 0  ) {
				var ie = $('#billing_ie');
				var ie_row = ie.closest( '.form-row ');
				var wrap = ie_row.closest( '.woocommerce-billing-fields__field-wrapper' );
				wrap.find( '.form-row ').each( function(i){
					if( $( this ).index() > ie_row.index() ) {
						var current_row = $( this );
						var css_class = '';
						if( current_row.hasClass( 'form-row-first' ) ) {
							css_class = current_row.attr( 'class' );
							css_class = css_class.replace( 'first', 'last' );
							current_row.attr( 'class', css_class );
						}
						else if( current_row.hasClass( 'form-row-last' ) ) {
							css_class = current_row.attr( 'class' );
							css_class = css_class.replace( 'last', 'first' );
							current_row.attr( 'class', css_class );
						}
					}
				} );
			}
		});
	};

	$(document).ready(function(){
		masks_init();
		init_cep();
		nome_completo_init();
		desativa_campos();
		fix_layout_inscricao_estadual();
	}); // $(document).ready

});