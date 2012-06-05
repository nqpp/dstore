<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
 * calendar preferences
 */

$config['calendar']['default'] = array(
  
);

$config['calendar']['todo_list'] = array(
  'show_next_prev'=>true,
  'next_prev_url'=>'todo?date=',
  'temp'=>array(
      'table_open'=>'<table class="calendar">',
      'heading_row_start'=>'<thead><tr class="head_row">',
      'heading_previous_cell'=>'<th><a href="{previous_url}" class="prev_link">&lt;&lt;</a></th>',
      'heading_title_cell'=>'<th colspan="{colspan}">{heading}</th>',
      'heading_next_cell'=>'<th><a href="{next_url}" class="next_link">&gt;&gt;</a></th>',
      'heading_row_end'=>'</tr>',
      'week_row_start'=>'<tr class="week_row">',
      'week_day_cell'=>'<th>{week_day}</th>',
      'week_row_end'=>'</tr></thead>',
      'cal_row_start'=>'<tr>',
      'cal_cell_start'=>'<td class="day">',
      'cal_cell_start_today'=>'<td class="today">',
      'cal_cell_content'=>'<a href="{content}">{day}</a>',
      'cal_cell_content_today'=>'<a href="{content}">{day}</a>',
      'cal_cell_no_content'=>'{day}',
      'cal_cell_no_content_today'=>'{day}',
      'cal_cell_blank'=>'&nbsp;',
      'cal_cell_end'=>'</td>',
      'cal_cell_end_today'=>'</td>',
      'cal_row_end'=>'</tr>',
      'table_close'=>'</table>'
  )
);
