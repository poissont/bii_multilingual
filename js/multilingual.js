jQuery(function ($) {
//	bii_CL("multilingual activé");
//	bii_CL(bii_lang);


	if (bii_lang == "fr") {
		$(".meta-sep").text("le");
		$('.um-button[value="Login"]').val("Se connecter");
		$('a.um-button[href$="enregistrement/"]').text("S'enregistrer");
		$(".um-follows-you").text($(".um-follows-you").text().replace("follows you", "vous suit"));

	}
	if (bii_lang == "en") {
		$(".kleo-main-header").wrap('<div id="undefined-sticky-wrapper" class="sticky-wrapper is-sticky" style="height: 88px;"></div>');
		$(".kleo-main-header").css("position", "fixed");
		$(".kleo-main-header").css("top", "0px");
		jQuery(window).on("scroll load", function () {
			biiaddsticky();
		});
		var closemenuitem = setTimeout(function(){});
		jQuery("#menu-item-970").on("mouseenter", function () {
//			bii_CL("enter");
			$(this).addClass("open");
			window.clearTimeout(closemenuitem);
			
		});
		jQuery("#menu-item-970").on("mouseleave", function () {
//			bii_CL("leave");
			closemenuitem = setTimeout(function () {
				$(this).removeClass("open");
			}, 1000);

		});


		$('[placeholder="Prénom"]').attr("placeholder", "First Name");
		$('[placeholder="Nom"]').attr("placeholder", "Last Name");
		$('[placeholder="Entreprise"]').attr("placeholder", "Company");
		$('[placeholder="Téléphone"]').attr("placeholder", "Phone");
		$('[placeholder="Nom du projet"]').attr("placeholder", "Project name");
		$('[placeholder="Description de votre projet"]').attr("placeholder", "Project description");
		$('[placeholder="Ville"]').attr("placeholder", "City");
		$('[placeholder="Code postal"]').attr("placeholder", "Zip code");
		$('[placeholder="Qu\'avez vous à dire ?"]').attr("placeholder", "What's on your mind?");
		$('[placeholder="Votre message..."]').attr("placeholder", "Write a message...");



		$('[original-title="Compte Vérifié"]').attr("original-title", "Checked Account");

		$(".um-followers-rc a").each(function () {
			var text = $(this).html();
			text = text.replace("abonnés", "followers");
			text = text.replace("abonnements", "following");
			$(this).html(text);
		});
		$(".um-profile-nav-item").each(function () {
			var text = $(this).html();
			text = text.replaceAll("À propos", "About");
			text = text.replaceAll("Commentaires", "Comments");
			$(this).html(text);
		});
		$(".um-account-meta a").each(function () {
			$(this).attr("href", "http://wonderwomenworld.com/voir-un-utilisateur/?lang=en");
		});
		$(".menu-item").each(function () {
			var text = $(this).html();
			text = text.replaceAll("Le Mag'", "Mag");
			text = text.replaceAll("Le Mag’", "Mag");
			text = text.replaceAll("Fil d’actualités", "Activity");
			text = text.replaceAll("Profil", "My account");
			text = text.replaceAll("Campagnes", "Donation Campains");
			text = text.replaceAll("Proposer un projet", "Project registration");
			text = text.replaceAll("Membres", "Members");
			text = text.replaceAll("Se déconnecter", "Logout");
			text = text.replace("data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7", "http://wonderwomenworld.com/wp-content/plugins/sitepress-multilingual-cms/res/flags/fr.png");
			text = text.replace("English", "Français");
			$(this).html(text);

		});
		$(".um-field").each(function () {
			var text = $(this).html();
			text = text.replace("À propos", "About");
			text = text.replace("Commentaires", "Comments");
			$(this).html(text);
		});
		$(".um-field-value").each(function () {
			var text = $(this).html();
			text = text.replace("Inscrit depuis le", "Joined");
			text = text.replace("non connecté", "Disconnected");
			text = text.replace("connecté", "Online");
			$(this).html(text);
		});
		if ($(".um-activity-head").length) {
			$(".um-activity-head").html($(".um-activity-head").html().replace("Publications sur votre mur", "Post on your wall "));
		}
		if ($(".um-activity-post").length) {
			$(".um-activity-post").html($(".um-activity-post").html().replace("Publier", "Publish"));

		}

		if ($(".um-message-send").length) {
			$(".um-message-send").html($(".um-message-send").html().replace("Envoyer", "Send"));
		}

	}

	function bii_fix_query_string(link) {
		var replace = "?lang=" + bii_lang;
//		bii_CL(replace);
		var replaced = link.replace(replace, "");
//		bii_CL(replaced);
		if (bii_lang != "fr") {
			if (link.indexOf("?") != -1) {
				if (link.indexOf("&")) {
					replaced = replaced.replace("&", "?");
				}
				replaced += "&lang=" + bii_lang;
			} else {
				replaced += "?lang=" + bii_lang;
			}
		}
		return replaced;
	}
	function bii_fix_date(date) {

	}
	function biiaddsticky() {
		var zone = zoneFenetre();
		var minheightlogo = 42.5;
		var minheightnavbar = 44;
		var maxheightlogo = 85;
		var maxheightnavbar = 88;
		if (zone.ytop > 42.5) {
			$(".kleo-main-header").addClass("header-scrolled");
			$("#logo_img").css("max-height", minheightlogo + "px");
			$(".navbar-header").css("height", minheightnavbar + "px");
			$(".navbar-header").css("line-height", minheightnavbar + "px");
		} else {
			$(".kleo-main-header").removeClass("header-scrolled");
			if (zone.ytop == 0) {
				$("#logo_img").css("max-height", maxheightlogo + "px");
				$(".navbar-header").css("height", maxheightnavbar + "px");
				$(".navbar-header").css("line-height", maxheightnavbar + "px");
			} else {
				var ratio = 1 - zone.ytop / 42.5;
				var heightnavbar = minheightnavbar + ratio * (maxheightnavbar - minheightnavbar);
				var heightlogo = minheightlogo + ratio * (maxheightlogo - minheightlogo);
				$("#logo_img").css("max-height", heightlogo + "px");
				$(".navbar-header").css("height", heightnavbar + "px");
				$(".navbar-header").css("line-height", heightnavbar + "px");

			}
		}

	}
});