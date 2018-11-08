<?php

class pluginPRELOAD extends Plugin {

	public function init()
	{
		$this->dbFields = array(
			'head'=>'',
			'bodybegin'=>'',
			'bodyend'=>''
		);
	}

	public function form()
	{
		global $L;

		$html  = '<div class="alert alert-primary" role="alert">';
		$html .= $this->description();
		$html .= '</div>';

		
		$html .= '<div>';
		$html .= '<label>'.$L->get('inside-head-tag').'</label>';
		$html .= '<textarea name="head" id="jshead">'.$this->getValue('head').'</textarea>';
		$html .= '<span class="tip">'.$L->get('inside-head-tag-desc').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('after-body-begin').'</label>';
		$html .= '<textarea name="bodybegin" id="jsheader">'.$this->getValue('bodybegin').'</textarea>';
		$html .= '<span class="tip">'.$L->get('after-body-begin-desc').'</span>';
		$html .= '</div>';

		$html .= '<div>';
		$html .= '<label>'.$L->get('before-body-end').'</label>';
		$html .= '<textarea name="bodyend" id="jsfooter">'.$this->getValue('bodyend').'</textarea>';
		$html .= '<span class="tip">'.$L->get('before-body-end-desc').'</span>';
		$html .= '</div>';

		return $html;
	}

	public function siteHead()
	{
		return html_entity_decode($this->getValue('head'));
	}

	public function siteBodyBegin()
	{
		return html_entity_decode($this->getValue('bodybegin'));

	}

	public function siteBodyEnd()
	{
		return html_entity_decode($this->getValue('bodyend'));
	}
}

