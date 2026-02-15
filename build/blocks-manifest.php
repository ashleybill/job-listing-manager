<?php
// This file is generated. Do not modify it manually.
return array(
	'job-apply-button' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'job-listing-manager/job-apply-button',
		'version' => '0.1.0',
		'title' => 'Job Apply Button',
		'category' => 'job-listing-blocks',
		'icon' => 'yes',
		'description' => 'Button to apply for job with position and location parameters',
		'attributes' => array(
			'buttonText' => array(
				'type' => 'string',
				'default' => 'Apply Now'
			),
			'applyPageId' => array(
				'type' => 'number',
				'default' => 0
			)
		),
		'supports' => array(
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true
			),
			'color' => array(
				'background' => true,
				'text' => true
			),
			'typography' => array(
				'fontSize' => true
			)
		),
		'usesContext' => array(
			'postId',
			'postType'
		),
		'textdomain' => 'job-listing-manager',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'render' => 'file:./render.php'
	),
	'job-closing-date' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'job-listing-manager/job-closing-date',
		'version' => '0.1.0',
		'title' => 'Job Closing Date',
		'category' => 'job-listing-blocks',
		'icon' => 'calendar-alt',
		'description' => 'Display job closing date',
		'supports' => array(
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true
			)
		),
		'usesContext' => array(
			'postId',
			'postType'
		),
		'textdomain' => 'job-listing-manager',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'job-location' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'job-listing-manager/job-location',
		'version' => '0.1.0',
		'title' => 'Job Location',
		'category' => 'job-listing-blocks',
		'icon' => 'location',
		'description' => 'Display job location',
		'supports' => array(
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true
			)
		),
		'usesContext' => array(
			'postId',
			'postType'
		),
		'textdomain' => 'job-listing-manager',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	),
	'job-salary' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'job-listing-manager/job-salary',
		'version' => '0.1.0',
		'title' => 'Job Salary',
		'category' => 'job-listing-blocks',
		'icon' => 'money-alt',
		'description' => 'Display job salary range',
		'supports' => array(
			'html' => false,
			'spacing' => array(
				'padding' => true,
				'margin' => true
			)
		),
		'usesContext' => array(
			'postId',
			'postType'
		),
		'textdomain' => 'job-listing-manager',
		'editorScript' => 'file:./index.js',
		'render' => 'file:./render.php'
	)
);
