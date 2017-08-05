<?php header("Content-type: application/js"); ?>

(function($) {

	if (typeof window.Advocacy == 'undefined') window.Advocacy = {};

	Advocacy = {

		type: '<?php print $this->action['type']?>',
		<?php if($this->action['type'] == "direct"): ?>
		targets: [
			{ 
				name: "<?php print $this->action['targets'][0]['name']; ?>",
				position: "<?php print $this->action['targets'][0]['position']; ?>"
				<?php if (!empty($this->action['targets'][0]['image'])): ?>,image: "<?php print $this->action['targets'][0]['image']; ?>"<?php endif; ?>
			}
		],
		<?php endif; ?>
		message: <?php print json_encode($this->action['message']); ?>,
		<?php if(!empty($this->action['test_email'])): ?>
		test: true,
		<?php endif; ?>

		lookupTarget: function(query, success) {

			$.getJSON("<?php print $this->protocol . "://" . $this->slug . ".nationrebuilder." . $this->extension . "/api/actions/search"; ?>",{postcode:query}, function(data){
				Advocacy.targets = [];
				$.each(data, function(i,item){
					Advocacy.targets[i] = {
						image: typeof item.image != "undefined" ? "//www.openaustralia.org.au"+item.image : false,
						name: item.full_name,
						position: "Member for "+item.constituency,
						id: item.person_id
					}	
				})
				success();
			})
		},

		submitForm: function($form, success, errors) {

			$.post("<?php print $this->protocol . "://" . $this->slug . ".nationrebuilder." . $this->extension . "/actions/" . $this->action['slug']; ?>", $form.serialize(),function(data){
				if (data.response == "success")
					success(data);
				else
					errors(data);
				
			});
		}

	};
	
})(jQuery);