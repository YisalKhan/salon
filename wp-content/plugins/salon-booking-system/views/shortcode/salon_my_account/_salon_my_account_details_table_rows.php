<?php foreach ( $data['table_data']['items'] as $item ): ?>

	<tr
		<?php echo isset($data['table_data']['page']) ? 'data-page="'.$data['table_data']['page'].'"' : ''; ?>
		<?php echo isset($data['table_data']['end']) ? 'data-end="'.$data['table_data']['end'].'"' : ''; ?>
	>
		<td data-th="<?php _e('ID','salon-booking-system');?>"><?php echo $item['id'] ?></td>
		<td data-th="<?php _e('When','salon-booking-system');?>"><div class="sln-booking-date"><?php echo $item['date'] ?></div><div class="sln-booking-time"><?php echo $item['time'] ?></div></td>
		<td data-th="<?php _e('Services','salon-booking-system');?>"><?php echo $item['services'] ?></td>
		<?php if($data['attendant_enabled']): ?>
			<td data-th="<?php _e('Assistants','salon-booking-system');?>"><?php echo $item['assistant'] ?></td>
		<?php endif; ?>
		<?php if(!$data['hide_prices']): ?>
			<td data-th="<?php _e('Price','salon-booking-system');?>"><nobr><?php echo $item['total'] ?></nobr></td>
		<?php endif; ?>
		<td data-th="<?php _e('Status','salon-booking-system');?>">
			<div class="status">
				<nobr>
					<span class="glyphicon <?php echo SLN_Enum_BookingStatus::getIcon($item['status_code']); ?>" aria-hidden="true"></span>
					<span class="glyphicon-class"><strong><?php echo $item['status']; ?></strong></span>
				</nobr>
			</div>
			<div>
				<?php if($data['table_data']['mode'] === 'history' || $item['timestamp'] < (time())): ?>
					<?php if(in_array($item['status_code'], array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED))): ?>
						<input type="hidden" name="sln-rating" value="<?php echo $item['rating']; ?>">
						<div class="rating" id="<?php echo $item['id']; ?>" style="display: none;"></div>
                        <div class="feedback"><?php echo $item['feedback'] ?></div>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</td>
		<td data-th="<?php _e('Action','salon-booking-system');?>" class="col-xs-12 col-md-3">
			<div class="row">
				<?php if($data['table_data']['mode'] === 'history'): ?>
					<!-- SECTION OLD START -->
					<?php if(in_array($item['status_code'], array(SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID, SLN_Enum_BookingStatus::CONFIRMED))): ?>
						<?php if(empty($item['rating'])): ?>
							<div class="col-xs-12 col-sm-6 col-md-12">
								<div class="sln-btn sln-btn--medium sln-btn--fullwidth sln-btn--borderonly sln-rate-service">
									<button onclick="slnMyAccount.showRateForm(<?php echo $item['id']; ?>);">
										<?php _e('Leave a feedback','salon-booking-system');?>
									</button>
								</div>
							</div>
							<div style="clear: both"></div>
						<?php endif; ?>
					<?php endif; ?>
					<!-- SECTION OLD END -->
				<?php elseif($data['table_data']['mode'] === 'new'): ?>
					<!-- SECTION NEW START -->
					<?php if ($item['timestamp'] < (time())): ?>
						<div class="col-xs-12 col-sm-6 col-md-12">
							<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
								<a href="<?php echo $data['booking_url'] ?>"><?php _e('Book now', 'salon-booking-system') ?></a>
							</div>
						</div>
					<?php
						continue;
						endif;
					?>

					<?php if (in_array($item['status_code'], array(SLN_Enum_BookingStatus::PENDING_PAYMENT)) && $data['pay_enabled']): ?>
						<?php
						$booking = SLN_Plugin::getInstance()->createBooking($item['id']); ?>
						<div class="col-xs-12 col-sm-6 col-md-12">
							<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth">
								<a href="<?php echo $booking->getPayUrl(); ?>">
									<?php _e('Pay Now','salon-booking-system');?>
								</a>
							</div>
						</div>
						<div style="clear: both"></div>

						<?php if (SLN_Plugin::getInstance()->getSettings()->get('pay_offset_enabled')) : ?>
							<div class="col-xs-12 col-sm-6 col-md-12">
								<?php echo sprintf(__('You have <strong>%s</strong> to complete your payment before this reservation being canceled','salon-booking-system'), $booking->getTimeStringToChangeStatusFromPending()); ?>
							</div>
							<div style="clear: both"></div>
							<br>
						<?php endif ?>
					<?php endif; ?>
					<?php if ($data['cancellation_enabled']): ?>
						<div class="col-xs-12 col-sm-6 col-md-12">
							<?php
							if ($item['timestamp'] - (time()) > $data['seconds_before_cancellation']): ?>
								<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth sln-btn--borderonly">
									<button onclick="slnMyAccount.cancelBooking(<?php echo $item['id']; ?>);">
										<?php _e('Cancel booking','salon-booking-system');?>
									</button>
								</div>
							<?php else: ?>
								<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth disabled">
									<button data-toggle="tooltip" data-placement="top" style="cursor: not-allowed;"
									        title="<?php echo sprintf(__('Sorry, you cannot cancel this booking online. Please call %s', 'salon-booking-system'),$data['gen_phone']); ?>">
										<?php _e('Cancel booking','salon-booking-system');?>
									</button>
								</div>
							<?php endif ?>
						</div>
						<div style="clear: both"></div>
					<?php endif; ?>
					<!-- SECTION NEW END -->
				<?php endif; ?>

			    <?php $booking = SLN_Plugin::getInstance()->createBooking($item['id']); ?>
			    <?php if ($data['is_form_steps_alt_order'] && apply_filters('sln.salon_my_acccount.show-reschedule-button', true, $booking)): ?>
				
				    <?php if ($data['table_data']['mode'] === 'new'): ?>

					<?php if (!SLN_Plugin::getInstance()->getSettings()->get('rescheduling_disabled')
						&& $booking->getStartsAt()->getTimeStamp() - (time()) >= SLN_Plugin::getInstance()->getSettings()->get('days_before_rescheduling') * 24 * 3600
						&& in_array($item['status_code'], array(SLN_Enum_BookingStatus::CONFIRMED, SLN_Enum_BookingStatus::PAY_LATER, SLN_Enum_BookingStatus::PAID))
					    ): ?>

					    <?php $date = $booking->getStartsAt(); ?>
<div class="col-xs-12 col-sm-6 col-md-12">
					    <div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth sln-reschedule-booking--button">
						<?php _e('RESCHEDULE', 'salon-booking-system');?>
					    </div>

				</div>
					    <?php ob_start(); ?>
					    <label for="<?php echo SLN_Form::makeID('sln[date][day]') ?>"><?php _e(
						    'select a day',
						    'salon-booking-system'
						) ?></label>
					    <?php SLN_Form::fieldJSDate('_sln_booking_date', $date) ?>
					    <?php $datepicker = ob_get_clean();
					    ob_start(); ?>
					    <label for="<?php echo SLN_Form::makeID('sln[date][time]') ?>"><?php _e(
						    'select an hour',
						    'salon-booking-system'
						) ?></label>
					    <?php SLN_Form::fieldJSTime('_sln_booking_time', $date, array('interval' => SLN_Plugin::getInstance()->getSettings()->get('interval'))) ?>
					    <?php $timepicker = ob_get_clean(); ?>

					    <form class="col-xs-12 sln-reschedule-form hide">

						<?php SLN_Form::fieldText('_sln_booking_id', $item['id'], array('type' => 'hidden')) ?>

						<?php foreach($booking->getBookingServices()->getItems() as $bookingService): ?>

						    <?php $serviceId = $bookingService->getService()->getId(); ?>

						    <?php SLN_Form::fieldText(
							'_sln_booking[services][' . $serviceId . ']',
							$bookingService->getAttendant() ? $bookingService->getAttendant()->getId() : 0,
							array('type' => 'hidden')
							)
						    ?>

						<?php endforeach; ?>

						<div class="row sln-box--main">
						    <div class="col-xs-12 sln-reschedule-form--title">
							<h3>
							    <?php _e('RESCHEDULE', 'salon-booking-system');?>
							</h3>
						    </div>
						    <div class="col-xs-12">
								<div class="row sln-reschedule-form_innerwrap">
									    <div class="col-xs-12 col-sm-6 sln-input sln-input--datepicker">
										<?php echo $datepicker ?>
									    </div>
									    <div class="col-xs-12 col-sm-6 sln-input sln-input--datepicker">
										<?php echo $timepicker ?>
									    </div>
									    <div class="col-xs-12 sln-notifications"></div>
									    <div class="col-xs-12 col-sm-6 sln-reschedule-form__btnwrp">
											<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth sln-reschedule-form--save-button">
											    <?php _e('SAVE BOOKING', 'salon-booking-system');?>
											</div>
									    </div>
							    </div>
							</div>
							<div class="col-xs-12">
								<div class="row">
								    <div class="col-xs-12 col-sm-6 sln-reschedule-form__btnwrp">
									<div class="sln-btn  sln-btn--borderonly sln-btn--medium sln-btn--fullwidth sln-btn--icon sln-btn--icon--left sln-icon--cancel sln-reschedule-form--cancel-button">
									    <?php _e('CANCEL', 'salon-booking-system');?>
									</div>
								    </div>
								</div>
						    </div>
					    </form>
					<?php endif; ?>
				    <?php else: ?>
				    	<div class="col-xs-12 col-sm-6 col-md-12">
					<div class="sln-btn sln-btn--emphasis sln-btn--medium sln-btn--fullwidth sln-reschedule-booking">
					    <a href="<?php echo $booking->getRescheduleUrl(); ?>">
						<?php _e('REPEAT BOOKING', 'salon-booking-system');?>
					    </a>
					</div>
				</div>
				    <?php endif;?>
				<div style="clear: both"></div>
			    <?php endif; ?>
			</div>
		</td>
	</tr>
<?php endforeach; ?>
