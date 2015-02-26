<?php
/**
 * Copyright (c) 2015 iControlWP <support@icontrolwp.com>
 * All rights reserved.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
 * ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
 * SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

if ( !class_exists( 'ICWP_CALQIO_Processor_Calqio_V1', false ) ):

	require_once( dirname(__FILE__).ICWP_DS.'base.php' );

	class ICWP_CALQIO_Processor_Calqio_V1 extends ICWP_CALQIO_Processor_Base {

		/**
		 * @var boolean
		 */
		protected $bRanOnce = false;

		/**
		 */
		public function run() {
			/** @var ICWP_CALQIO_FeatureHandler_Calqio $oFO */
			$oFO = $this->getFeatureOptions();

			$sTrackingKey = $oFO->getTrackingWriteKey();
			if ( empty( $sTrackingKey ) ) {
				return;
			}

			// First always add the tracking code.
			add_action( 'wp_head', array( $this, 'printCalqJsSnippet' ) );
		}

		/**
		 * Can only ever run once
		 */
		public function printCalqJsSnippet() {
			/** @var ICWP_CALQIO_FeatureHandler_Calqio $oFO */
			$oFO = $this->getFeatureOptions();
			if ( $this->bRanOnce || ( $oFO->getIgnoreLoggedInUser() && is_user_logged_in() ) ) {
				return;
			}
			$this->bRanOnce = true;

			$sSnippet = $this->getBaseCalqJsSnippet();
			if ( !empty( $sSnippet ) ) {
				echo $sSnippet;
			}
		}

		/**
		 * @return string
		 */
		protected function getBaseCalqJsSnippet() {
			/** @var ICWP_CALQIO_FeatureHandler_Calqio $oFO */
			$oFO = $this->getFeatureOptions();

			$sCookieDomain = $oFO->getCookieDomain();
			$sCalqioJs = $this->getCalqTrackingCodeTemplate();
			$sCalqioJs = sprintf( $sCalqioJs,
				$oFO->getTrackingWriteKey(),
				empty( $sCookieDomain ) ? '' : sprintf( ', { cookieDomain: "%s" }', $sCookieDomain ),
				$this->getAdditionalCalqDirectives()
			);
			return $sCalqioJs;
		}

		/**
		 * @return string
		 */
		protected function getAdditionalCalqDirectives() {
			/** @var ICWP_CALQIO_FeatureHandler_Calqio $oFO */
			$oFO = $this->getFeatureOptions();
			$oWp = $this->loadWpFunctionsProcessor();

			$aDirectives = array();

			$oUser = $oWp->getCurrentWpUser();
			if ( !is_null( $oUser ) ) {
//					$aDirectives[] = sprintf( 'calq.user.identify( "%s" );', 'WP_'.$oUser->ID );
				$aDirectives[] = sprintf( 'calq.user.profile( { "$full_name": "%s", "$email": "%s" } );', $oUser->get( 'display_name' ), $oUser->get( 'user_email' ) );  // defined in base_controller
			}

			if ( $oFO->getTrackEveryPageView() ) {
				$aDirectives[] = 'calq.action.trackPageView();';
			}

			return implode( ' ', $aDirectives );
		}

		protected function getCalqTrackingCodeTemplate() {
			ob_start();
			?>
			<script type="text/javascript">
				(function (e, t) { if (!t.__SV) { window.calq = t; var n = e.createElement("script"); n.type = "text/javascript"; n.src = "http" + ("https:" === e.location.protocol ? "s" : "") + '://api.calq.io/lib/js/core-1.0.js'; n.async = !0; var r = e.getElementsByTagName("script")[0]; r.parentNode.insertBefore(n, r); t.init = function (e, o) { if (t.writeKey) return; t.writeKey = e; t._initOptions = o; t._execQueue = []; m = "action.track action.trackSale action.trackHTMLLink action.trackPageView action.setGlobalProperty user.profile user.identify user.clear".split(" "); for (var n = 0; n < m.length; n++) { var f = function () { var r = m[n]; var s = function () { t._execQueue.push({ m: r, args: arguments }) }; var i = r.split("."); if (i.length == 2) { if (!t[i[0]]) { t[i[0]] = [] } t[i[0]][i[1]] = s } else { t[r] = s } }(); } }; t.__SV = 1 } })(document, window.calq || []);
				if ( typeof calq != "undefined" ) {
					calq.init( "%s"%s ); %s
				}
			</script>
			<?php
			return ob_get_clean();
		}
	}

endif;

if ( !class_exists( 'ICWP_CALQIO_Processor_Calqio', false ) ):
	class ICWP_CALQIO_Processor_Calqio extends ICWP_CALQIO_Processor_Calqio_V1 { }
endif;