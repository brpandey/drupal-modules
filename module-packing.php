<?php

/**
 * Implementation of hook_menu()
 */

function packing_menu() {
	$items['packing'] = array(
		'title' => 'Packing',
		'page callback' => 'drupal_get_form',
		'page arguments' => array('packing_myform'),
		'type' => MENU_NORMAL_ITEM,
		'access arguments' => array('access content'),
		);

	$items['packing/thanks'] = array(
		'title' => t('Thanks!'),
		'page callback' => 'packing thanks',
		'type' => MENU_CALLBACK,
		'access arguments' => array('access_content'),
		);

	return $items;
}

/**
 * Form definition. We build the form differently depending
 * on which step we're on
 */

function packing_myform(&$form_state = NULL) {
	//1) Handle the step state
	//Find out which step we are on. If $form_state is not set,
	//that means we are beginning.  Since the form is rebuilt, we 
	//start at 0 in that case and the step is 1 during rebuild

	if(TRUE == isset($form_state['values']))
	{
		$step = (int) $form_state['storage']['step']
	}
	else
	{
		$step = 0;
	}

	//Iterate step value and store it
	$form_state['storage']['step'] = $step + 1;

	$form['my_hidden_field'] = array(
		'#type' => 'hidden',
		'#value' => t('I am a hidden field value'),
	);

	//5) Store stub values
	switch($step) {
		case 1: 
			packing_step1($form);
			/*
				$form_state['storage']['destination'] = $form_state['values']['destination']
				$form_state['storage']['date'] = $form_state['values']['date']
			*/
			break;
		case 2:
			//packing_step2($form);
			//$form_state['storage']['museum'] = $form_state['values']['museum']
			break;
	}

	return $form;
}

function packing_step1(&$form)
{
	$form['packing_indicator'] = array(
		'#type' => 'fieldset',
		'#title' => t('Essential Packing Checklist'),
		);

	$packing_options = array(
		'item1' => t('5 shirts'),
		'item2' => t('2 pairs pants'),
		'item3' => t('1 pair shorts'),
		'item4' => t('5 pairs underwear and socks'),
		'item5' => t('1 pair shoes')
		'item6' => t('1 rain-proof jacket'),
		'item7' => t('Passport'),
		'item8' => t('Airplane ticket'),
		'item9' => t('Smartphone'),
		'item10' => t('Toiletries kit'),

		/* etc... */

		);

	$form['packing_indicator']['museums'] = array(
		'#type' => 'checkboxes',
		'#description' => t('Whether you\'re traveling for five days or five weeks, 
			be sure to bring these essentials!'),
		'#options' => $packing_options,
		'#weight' => 25,
		);

	$button_name = t('Submit');

	$form['submit'] = array(
		'#type' => 'submit',
		'#value' => $button_name
		);
}

/**
 *
 * Validate handle for form ID 'packing_form'
 */

function packing_myform_validate($form, &$form_state)
{
	//Show user which step we are on
	drupal_set_message(
		t('Validation called for step @step',
			array('@step' => $form_state['storage']['step'] - 1)));
}

/**
 *
 * Submit handler for form ID 'packing_form'
 */

function packing_myform_submit($form, &$form_state)
{
	//If we are not at the end, do not submit

	if($form_state['storage']['step'] < 2)
	{
		return;
	}

	drupal_set_message(
		t('Your packing details are being processed, you will get an email shortly',
			array(
				'%stub_1' => $form_state['storage']['destination'],
				'%stub_2' => $form_state['storage']['trip_date'],
				'%stub_3' => $form_state['storage']['museums'],
				)
			)
		);

	//Clear storage bin
	unset($form_state['storage']);

	//Redirect to thank you page
	$form_state['redirect'] = 'packing/thanks';

}

function packing_thanks()
{
	return t('Thank you for packing!')
}