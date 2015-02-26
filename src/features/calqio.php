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

if ( !class_exists( 'ICWP_CALQIO_FeatureHandler_Calqio', false ) ):

	require_once( dirname(__FILE__).ICWP_DS.'base.php' );

	class ICWP_CALQIO_FeatureHandler_Calqio extends ICWP_CALQIO_FeatureHandler_Base {
		/**
		 * @return string
		 */
		protected function getProcessorClassName() {
			return 'ICWP_CALQIO_Processor_Calqio';
		}

		/**
		 * @return boolean
		 */
		public function getIsMainFeatureEnabled() {
			$bEnabled = $this->getTrackingWriteKeyValid() && parent::getIsMainFeatureEnabled();
			return $bEnabled;
		}

		protected function getTrackingWriteKeyValid() {
			$sWriteKey = $this->getTrackingWriteKey();
			return ( !empty( $sWriteKey ) && ( strlen( $sWriteKey ) == 32 ) && ( strpos( $sWriteKey, 'Invalid Key' ) === false) ) ;
		}

		public function doExtraSubmitProcessing() {
			//Test out the Calq key.
			$sKeyVerification = $this->verifyCalqKey( $this->getTrackingWriteKey() );
			$this->setOpt( 'write_key', $sKeyVerification );
		}

		protected function verifyCalqKey( $sKey ) {
			$this->getController()->loadLib( 'calq/CalqClient.php' );
			if ( !class_exists( 'CalqClient' ) ) {
				return false;
			}
			try {
				$oClient = CalqClient::fromCurrentRequest( $sKey );
			}
			catch( Exception $oE ) {
				return sprintf( _calqio__( 'Invalid Key: %s' ), _calqio__( 'Ensure the key is exactly 32 digits long' ) );
			}
			try {
				$oClient->track( 'Verify WordPress Configuration' );
				$oClient->flush();
			}
			catch( Exception $oE ) {
				return sprintf( _calqio__( 'Invalid Key: %s' ), $oE->getMessage() );
			}
			return $sKey;
		}


		/**
		 * @return string
		 */
		public function getTrackingWriteKey() {
			return $this->getOpt( 'write_key' );
		}

		/**
		 * @return string
		 */
		public function getCookieDomain() {
			$sDomain = $this->getOpt( 'tracking_cookie_domain' );
			return empty( $sDomain ) ? COOKIE_DOMAIN : $sDomain;
		}

		/**
		 * @return string
		 */
		public function getIgnoreLoggedInUser() {
			return $this->getOptIs( 'ignore_logged_in_user', 'Y' );
		}

		/**
		 * @return string
		 */
		public function getTrackEveryPageView() {
			return $this->getOptIs( 'track_every_page_view', 'Y' );
		}

		/**
		 * @param array $aOptionsParams
		 * @return array
		 * @throws Exception
		 */
		protected function loadStrings_SectionTitles( $aOptionsParams ) {

			$sSectionSlug = $aOptionsParams['section_slug'];
			switch( $aOptionsParams['section_slug'] ) {

				case 'section_enable_plugin_feature_calqio' :
					$sTitle = sprintf( _calqio__( 'Enable Plugin Feature: %s' ), _calqio__('Calq.io Tracking') );
					break;

				case 'section_calqio_basic_configuration' :
					$sTitle = _calqio__( 'Configure Basic Options For This Calq.io Project' );
					break;

				default:
					throw new Exception( sprintf( 'A section slug was defined but with no associated strings. Slug: "%s".', $sSectionSlug ) );
			}
			$aOptionsParams['section_title'] = $sTitle;
			return $aOptionsParams;
		}

		/**
		 * @param array $aOptionsParams
		 * @return array
		 * @throws Exception
		 */
		protected function loadStrings_Options( $aOptionsParams ) {

			$sKey = $aOptionsParams['key'];
			switch( $sKey ) {

				case 'enable_calqio' :
					$sName = sprintf( _calqio__( 'Enable %s' ), _calqio__( 'Calq.io Tracking' ) );
					$sSummary = sprintf( _calqio__( 'Enable (or Disable) The %s Feature' ), _calqio__( 'Calq.io Tracking' ) );
					$sDescription = sprintf( _calqio__( 'Checking/Un-Checking this option will completely turn on/off the whole %s feature.' ), _calqio__( 'Calq.io Tracking' ) );
					break;

				case 'write_key' :
					$sName = _calqio__( 'Calq.io Write Key' );
					$sSummary = _calqio__( 'The Unique Calq.io Tracking Key For Your Project.' );
					$sDescription = _calqio__( 'For each Calq.io project, you have a "write key" and a "read key.' ). ' '._calqio__('To track page view or events on this site, supply the "write key" here.') ;
					break;

				case 'read_key' :
					$sName = _calqio__( 'Calq.io Read Key' );
					$sSummary = _calqio__( '' );
					$sDescription = _calqio__( '' );
					break;

				case 'tracking_cookie_domain' :
					$sName = _calqio__( 'Cookie Domain' );
					$sSummary = _calqio__( 'The Domain Name To Store Tracking Cookie' );
					$sDescription = sprintf( _calqio__( 'If you leave this empty, your default WordPress Cookie Domain will be used: %s' ), '<span style"text-decoration:underline;">'.COOKIE_DOMAIN.'</span>' );
					break;

				case 'track_every_page_view' :
					$sName = _calqio__( 'Track Page Views' );
					$sSummary = _calqio__( 'Track Every Page View' );
					$sDescription = _calqio__( 'Every page load will be tracked when this option is enabled.' );
					break;

				case 'ignore_logged_in_user' :
					$sName = _calqio__( 'Ignore Users' );
					$sSummary = _calqio__( 'Ignore Logged-In Users' );
					$sDescription = _calqio__( 'If a visitor is considered to be logged-in by WordPress, no events will be tracked.' );
					break;

				default:
					throw new Exception( sprintf( 'An option has been defined but without strings assigned to it. Option key: "%s".', $sKey ) );
			}

			$aOptionsParams['name'] = $sName;
			$aOptionsParams['summary'] = $sSummary;
			$aOptionsParams['description'] = $sDescription;
			return $aOptionsParams;
		}
	}

endif;