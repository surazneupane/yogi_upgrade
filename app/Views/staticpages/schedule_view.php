<?php



/*echo "<pre>";
print_r($schedules);
exit;*/

?>

<!--	CONTENT WRAPPER	[START]	  -->
<div class="content-wrapper">
	
	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->
	<div class="articledetails-layout-wrapper scheudule-content-wrapper">
	
		<section class="banner-another ">
	        <!-- Banner section Start-->
	    </section>
	
	    <section id="blog_single" class="scheudule-page">
	    
	    	<!-- container Start-->
	        <div class="container">
	            
	        	<h3 data-aos="fade-up" data-aos-delay="300" class="title text-center" id="yogasite-ga-article-headline" itemprop="headline"><?php echo lang('InformationLang.SCHEDULE'); ?></h3>
	        	
	        	<div class="scheudule-content-container">
	        		
	        		<div class="schedule-table-wrapper">
	        			<div class="table-responsive">
	        			
	        				<?php echo $schedules[0]['schedule_data']; ?>
							
							
							<!-- EN -->
							<!--<table class="table table-bordered">
							<tbody>
							<tr class="title-tr">
							<td width="5%">&nbsp;</td>
							<td class="bg-1 text-center" width="16.6%"><a title="Coursegoules" href="/article/shedules-and-locations/coursegoules">Coursegoules</a></td>
							<td class="bg-2 text-center" width="16.6%"><a title="Tourrettes sur Loup" href="/article/shedules-and-locations/tourrettes-sur-loup">Tourrettes sur Loup</a></td>
							<td class="bg-3 text-center" width="16.6%"><a title="Saint-Paul de Vence" href="/article/shedules-and-locations/saint-paul-de-vence">Saint-Paul de Vence</a></td>
							<td class="bg-1 text-center" width="16.6%"><a title="La Colle sur Loup" href="/article/shedules-and-locations/la-colle-sur-loup">La Colle sur Loup</a></td>
							<td class="bg-2 text-center" width="16.6%">La Gaude***</td>
							<td class="bg-3 text-center" width="16.6%">Vence***</td>
							</tr>
							<tr class="subtitle-tr">
							<td>&nbsp;</td>
							<td class="text-center" style="text-align: center;">Salle Ecole Communale, 338 Route de l'Ourm&eacute;ou</td>
							<td class="text-center" style="text-align: center;">Moulin Baussy, 4-6, rue du Fr&ecirc;ne</td>
							<td class="text-center" style="text-align: center;">Salle du Moulin, Chemin de la Fontette, sous la c&eacute;l&egrave;bre "place du jeu de boules"</td>
							<td class="text-center" style="text-align: center;">Salle du Jeu de Paume (1) ou Salle de la Paill&egrave;re et jardin (2) see addresses here bellow</td>
							<td class="text-center" style="text-align: center;">La Bastide Haute, Av. Marcel Pagnol</td>
							<td class="text-center" style="text-align: center;">Salle du Mineur, 577 Av Henri Giraud</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Monday</td>
							<td class="text-center">&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
							<td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 19H-20H30</td>
							<td>&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Tuesday</td>
							<td style="text-align: center;">18H15-19H30</td>
							<td class="text-center" style="text-align: center;">9H - 10H15</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Wednesday</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">9H30 - 11H30 kids*</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Thursday</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">18H15-19H30</td>
							<td class="text-center" style="text-align: center;">8H - 9H15 &amp; 9H30 - 10H45</td>
							<td style="text-align: center;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Friday</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">8H - 9H15 &amp;<br />9H30 - 10H45&nbsp;(1) <br />Evelyne</td>
							<td class="text-center" style="text-align: center;">9H30-11H*</td>
							<td class="text-center" style="text-align: center;">12H30-14H*</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Saturday</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">10H30 - 12H30 (2)**</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							</tr>
							</tbody>
							</table>
							<p class="mb-1 text-dark"><strong> Idealy arrive 10 minutes before the start of the course</strong></p>
							<p class="mb-1 text-dark">&nbsp;</p>
							<p class="mb-1 text-dark"><strong>* Children 4 to 11 years old, among availability. Annual or quarterly subscription only</strong></p>
							<p class="mb-1 text-dark"><strong>** Workshops 1 or 2 times a month Garden and Yoga room (see the schedule 2020, part "Yoga workshops")</strong></p>
							<p class="mb-1 text-dark"><span style="text-decoration: underline;"> <strong> (1) Salle du jeu de Paume:</strong></span> Parking du&nbsp;jeu&nbsp;de Paume Village Center LA COLLE SUR LOUP - Free Parking 2H</p>
							<p><span style="text-decoration: underline;"><strong>(2) Salle de la Paill&egrave;re et jardin</strong></span><strong>: </strong>Rue Laurenti (at 1st floor of the dance hall on rue Cl&eacute;ment Menceau), LA COLLE SUR LOUP</p>
							<p>Upload planning (additional courses with Vence and La Gaude Yoga***)</p>
							<p><em><strong>FROM&nbsp; 7th Sept, LESSONS USUAL PLACES AND TIMES (SEE FLYER)&nbsp;:&nbsp;<a title="flyer_between_yogis_2020" href="/assets/media/flyer_between_yogis_2020.pdf">flyer_between_yogis_2020</a>&nbsp;&nbsp;</strong></em></p>
							<p><em><strong>For some weeks we propose videos to practice at home, enjoy !&nbsp; <a title="Asanas &amp; Pranayama Videos" href="https://www.youtube.com/watch?v=DWhYEtwIXag&amp;lc=UgwX7rm7IUR1l756jrN4AaABAg">Between Yogis You tube Channel</a>&nbsp;</strong></em></p>
							<p><em>We also organise live lessons via&nbsp;GoToMeeting&nbsp;platform. If interested, thanks to send your request by mail or SMS.</em></p>
							<p>&nbsp;</p>-->
							
							
							<!-- FR -->
							<!--<p>&nbsp;</p>
							<table class="table table-bordered">
							<tbody>
							<tr class="title-tr">
							<td width="5%">&nbsp;</td>
							<td class="bg-1 text-center" style="text-align: center;" width="16.6%"><a title="Coursegoules" href="/article/shedules-and-locations/coursegoules">Coursegoules</a></td>
							<td class="bg-2 text-center" style="text-align: center;" width="16.6%"><a title="Tourrettes sur Loup" href="/article/shedules-and-locations/tourrettes-sur-loup">Tourrettes sur Loup</a></td>
							<td class="bg-3 text-center" style="text-align: center;" width="16.6%"><a title="Saint-Paul de Vence" href="/article/shedules-and-locations/saint-paul-de-vence">Saint-Paul de Vence</a></td>
							<td class="bg-1 text-center" style="text-align: center;" width="16.6%"><a title="La Colle sur Loup" href="/article/shedules-and-locations/la-colle-sur-loup">La Colle sur Loup</a></td>
							<td class="bg-2 text-center" style="text-align: center;" width="16.6%">La Gaude***</td>
							<td class="bg-3 text-center" style="text-align: center;" width="16.6%">Vence***</td>
							</tr>
							<tr class="subtitle-tr">
							<td>&nbsp;</td>
							<td class="text-center" style="text-align: center;">Salle Ecole Communale, 338 Route de l'Ourm&eacute;ou</td>
							<td class="text-center" style="text-align: center;">Moulin Baussy, 4-6 rue du Fr&ecirc;ne</td>
							<td class="text-center" style="text-align: center;">Salle du Moulin, Chemin de la Fontette, sous la c&eacute;l&egrave;bre "place du jeu de boules"</td>
							<td class="text-center" style="text-align: center;">Salle du Jeu de Paume (1) ou Salle de la Paill&egrave;re et jardin (2) voir adresses ci-dessous</td>
							<td class="text-center" style="text-align: center;">La Bastide Haute, Av. Marcel Pagnol</td>
							<td class="text-center" style="text-align: center;">Salle du Mineur, 577 Av Henri Giraud</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Lundi</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;19H-20H30</td>
							<td style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Mardi</td>
							<td style="text-align: center;">&nbsp; &nbsp;18H15-19H30</td>
							<td class="text-center" style="text-align: center;">9H-10H15</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Mercredi</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;9H30-11H enfants *</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Jeudi</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;18H15-19H30</td>
							<td class="text-center" style="text-align: center;">8H - 9H15 &amp; <br />9H30 - 10H45</td>
							<td style="text-align: center;">&nbsp; &nbsp;&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Vendredi</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">8H - 9H15&nbsp; &nbsp; &nbsp; &nbsp;&amp;&nbsp;<br />9H30 - 10H45&nbsp;(1) Evelyne</td>
							<td class="text-center" style="text-align: center;">9H30-11H*</td>
							<td class="text-center" style="text-align: center;">12H30-14H*</td>
							</tr>
							<tr class="data-tr">
							<td class="data-title bg-5">Samedi</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">10H30-12H30 (2)**</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							<td class="text-center" style="text-align: center;">&nbsp;</td>
							</tr>
							</tbody>
							</table>
							<p class="mb-1 text-dark"><strong>Arriver id&eacute;alement 10 min avant le d&eacute;but du cours</strong></p>
							<p class="mb-1 text-dark"><strong>* Enfants 4 &agrave; 11 ans, sur inscription et selon disponibilit&eacute;s. Abonnement &agrave; l'ann&eacute;e ou au trimestre uniquement</strong></p>
							<p class="mb-1 text-dark"><strong>** Ateliers &agrave; th&egrave;me 1 ou 2 fois par mois en int&eacute;rieur et AU JARDIN SI BEAU TEMPS (voir le planning 2020, rubrique stages)</strong></p>
							<p class="mb-1 text-dark"><span style="text-decoration: underline;"><strong>(1) Salle du jeu de Paume</strong></span> : Parking du jeu de Paume, Centre Village LA COLLE SUR LOUP - Stationnement gratuit 2H</p>
							<p class=" text-dark"><span style="text-decoration: underline;"><strong>(2) Salle de la Paill&egrave;re et jardin</strong></span> : rue Laurenti (au 1&egrave; &eacute;t. de la salle de danse de la rue Cl&eacute;menceau), LA COLLE SUR LOUP</p>
							<p><em><strong>A partir du 7 Septembre, reprise des cours aux lieux et horaires habituels.</strong></em></p>
							<p><em><strong>Cr&eacute;neaux suppl&eacute;mentaires avec Vence et La Gaude Yoga&nbsp; (VOIR FLYER)***&nbsp;<a title="flyer_between_yogis_2020" href="/assets/media/flyer_between_yogis_2020.pdf">flyer_between_yogis_2020</a>&nbsp;</strong></em><em><strong>&nbsp;</strong></em></p>
							<p><span style="font-size: 12px;"><strong>Pendant cette p&eacute;riode, nous vous proposons une s&eacute;rie de vid&eacute;os, accessibles gratuitement pour pratiquer &agrave; la maison, seul ou en famille&nbsp;<a title="Vid&eacute;os Yoga enfants et adultes" href="https://www.youtube.com/watch?v=DWhYEtwIXag&amp;lc=UgwX7rm7IUR1l756jrN4AaABAg">Vid&eacute;os Yoga You Tube enfants et adultes Between Yogis</a></strong></span></p>
							<p><span style="font-size: 12px;"><strong><em><strong>N'h&eacute;sitez pas &agrave; vous abonner &agrave; la cha&icirc;ne et &agrave; consulter r&eacute;guli&egrave;rement pour visionner les nouveaut&eacute;s! Likez et partagez si vous aimez!!</strong></em></strong></span></p>
							<p><em>Nous mettons &eacute;galement en place des&nbsp;<span style="text-decoration: underline;">cours en direct live par la plateforme GoToMeeting</span>&nbsp;. Si vous &ecirc;tes int&eacute;ress&eacute;s, merci de nous envoyer votre demande par mail ou SMS.</em></p>
							<p>&nbsp;</p>-->
							
	        				
	        				<!--<table class="table table-bordered">
	        					<tr class="title-tr">
	        						<td width="16%"></td>
	        						<td width="21%" class="bg-1 text-center">Coursegoules</td>
	        						<td width="21%" class="bg-2 text-center">Tourrettes sur Loup</td>
	        						<td width="21%" class="bg-3 text-center">Saint Paul de Vence</td>
	        						<td width="21%" class="bg-4 text-center">La Colle sur Loup</td>
	        					</tr>
	        					<tr class="subtitle-tr">
	        						<td></td>
	        						<td class="text-center">Salle Ecole Communale, 338 Route de l'Ourméou</td>
	        						<td class="text-center">Salle de Yoga Maison Boursac, 2è Et, 2 route de Grasse</td>
	        						<td class="text-center">Salle du Moulin, Chemin de la Fontette, sous la célèbre "place du jeu de boules"</td>
	        						<td class="text-center">Salle du Jeu de Paume (1) ou Salle de la Paillère et jardin (2) voir adresses ci-dessous</td>
	        					</tr>
	        					<tr class="data-tr">
	        						<td class="data-title bg-5">Lundi</td>
	        						<td class="text-center">18H15-19H30</td>
	        						<td></td>
	        						<td></td>
	        						<td></td>
	        					</tr>
	        					<tr class="data-tr">
	        						<td class="data-title bg-5">Mardi</td>
	        						<td></td>
	        						<td class="text-center">8H45-10H</td>
	        						<td></td>
	        						<td class="text-center">17H45-19H*</td>
	        					</tr>
	        					<tr class="data-tr">
	        						<td class="data-title bg-5">Jeudi</td>
	        						<td></td>
	        						<td></td>
	        						<td class="text-center">9H30-10H45</td>
	        						<td></td>
	        					</tr>
	        					<tr class="data-tr">
	        						<td class="data-title bg-5">Vendredi</td>
	        						<td></td>
	        						<td></td>
	        						<td></td>
	        						<td class="text-center">9H30-10H45 (1)</td>
	        					</tr>
	        					<tr class="data-tr">
	        						<td class="data-title bg-5">Samedi</td>
	        						<td></td>
	        						<td></td>
	        						<td></td>
	        						<td class="text-center">10H30-12H30 (2)**</td>
	        					</tr>
	        				</table>-->
	        				
	        			</div>
	        		</div>
	        		
	        	</div>
	        
	        </div>
	        <!-- container Ended-->
	    
	        <div class="pricing py-2">
	    
		      <h3 data-aos="fade-up" data-aos-delay="300" class="title text-center"><?php echo lang('InformationLang.PRICING'); ?></h3>
		    
			  <div class="container" data-aos="fade-left" data-aos-delay="300">
			  
			  	<?php echo $schedules[0]['pricing_data']; ?>
			  
			    <!--<div class="row">
			      
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo lang('InformationLang.PLAN_1'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo lang('InformationLang.PLAN_1_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3 ml-0">
				              <li class="m-0 text-center"><?php echo lang('InformationLang.PLAN_1_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo lang('InformationLang.PLAN_1_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo lang('InformationLang.BOOK_NOW'); ?>"><?php echo lang('InformationLang.BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo lang('InformationLang.PLAN_3'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo lang('InformationLang.PLAN_3_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo lang('InformationLang.PLAN_3_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo lang('InformationLang.PLAN_3_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo lang('InformationLang.BOOK_NOW'); ?>"><?php echo lang('InformationLang.BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo lang('InformationLang.PLAN_4'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo lang('InformationLang.PLAN_4_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo lang('InformationLang.PLAN_4_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo lang('InformationLang.PLAN_4_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo lang('InformationLang.BOOK_NOW'); ?>"><?php echo lang('InformationLang.BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo lang('InformationLang.MONTHS_3'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo lang('InformationLang.MONTHS_3_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo lang('InformationLang.MONTHS_3_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo lang('InformationLang.MONTHS_3_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo lang('InformationLang.BOOK_NOW'); ?>"><?php echo lang('InformationLang.BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo lang('InformationLang.PLAN_5'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo lang('InformationLang.PLAN_5_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo lang('InformationLang.PLAN_5_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo lang('InformationLang.PLAN_5_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo lang('InformationLang.BOOK_NOW'); ?>"><?php echo lang('InformationLang.BOOK_NOW'); ?></a>
				        </div>
			          </div>
			        </div>
			      </div>
			      
			      <div class="col-lg-4 col">
			        <div class="card mb-4 mb-xl-4">
			          <div class="card-body text-white p-0">
			          	<div class="p-3 top-content">
				            <h5 class="card-title text-uppercase text-center"><?php echo lang('InformationLang.YEAR_1'); ?></h5>
				            <hr class="m-3"/>
				            <h6 class="card-price text-center"><?php echo lang('InformationLang.YEAR_1_PRICE'); ?></h6>
				        </div>
				        <div class="p-3 bottom-content">
				            <ul class="fa-ul mb-3">
				              <li class="m-0"><span class="fa-li"><i class="fa fa-check"></i></span><?php echo lang('InformationLang.YEAR_1_INFO'); ?></li>
				            </ul>
				            <a href="<?php echo lang('InformationLang.YEAR_1_BOOK_HREF'); ?>" class="btn btn-block text-white" title="<?php echo lang('InformationLang.BOOK_NOW'); ?>"><?php echo lang('InformationLang.BOOK_NOW'); ?></a>
				          
				        </div>
			          </div>
			        </div>
			      </div>
			    </div>-->
			    
			  </div>
			</div>
	    </section>
	    
	    <section class="blog-page-another"></section>
	
	</div>
	<!--	ARTICLE LAYOUT WRAPPER	[START]	  -->

</div>
<!--	CONTENT WRAPPER	[END]	  -->
