<?php

class Interactiv4_GAConversionTrack_Block_System_Config_Form_Field_Statustotrack extends Mage_Adminhtml_Block_System_Config_Form_Field_Array_Abstract
{
    protected $_selected = ' selected="selected" ';/*!< String for the selected option*/
    /**
     * Constructor wich adds the 2 columns an sets the template
     */
    public function __construct()
    {
        $this->addColumn('state', array(
            'label' => Mage::helper('adminhtml')->__('State'),
            'style' => 'width:120px',
            'class' => 'option-control'
        ));
        $this->addColumn('status', array(
            'label' => Mage::helper('adminhtml')->__('Status'),
            'style' => 'width:160px',
            'class' => 'option-control',
        ));
        $this->_addAfter = false;
        $this->_addButtonLabel = null;

        parent::__construct();
    }

    /**
     * Returns option elements for state and status
     *
     * @param string $methodsType
     * @return array
     */
    public function getMethodsOptionsHtml($methodsType)
    {
        $methods = null;
        $result = array();
        if($methodsType == 'status'){
            $data = Mage::getModel('sales/order_config')->getStatuses();
        }elseif($methodsType == 'state'){
            $data = Mage::getModel('sales/order_config')->getStates();
        }
        foreach ($data as $key => $value){
            if($key == "")
                continue;
            else
                $result[] = '<option value="'.$key.'" #{'.$methodsType.'_'.$key.'} >'.$value.'</option>';
        }


        sort($result);
        return implode($result);
    }
    /**
     * Renders the cell for the template
     *
     * @param string $columnName
     * @return string
     */
    protected function _renderCellTemplate($columnName)
    {
        if (empty($this->_columns[$columnName])) {
            throw new Exception('Wrong column name specified.');
        }
        $column     = $this->_columns[$columnName];
        $inputName  = $this->getElement()->getName() . '[#{_id}][' . $columnName . ']';


        $result = '<select id="statustotrack_'.$columnName.'" name="' . $inputName .
            ($column['size'] ? 'size="' . $column['size'] . '"' : '') . ' class="' .
            (isset($column['class']) ? $column['class'] : 'input-text') . '"'.
            (isset($column['style']) ? ' style="'.$column['style'] . '"' : '') . '>';

        $result .= $this->getMethodsOptionsHtml($columnName).'</select>';

        return $result;
    }

    /**
     * Obtain existing data from form element. Each row will be instance of Varien_Object.
     *
     * @return array
     */
    public function getArrayRows()
    {
        if (null !== $this->_arrayRowsCache) {
            return $this->_arrayRowsCache;
        }
        $result = array();
        $element = $this->getElement();
        if ($element->getValue() && is_array($element->getValue())) {
            foreach ($element->getValue() as $rowId => $row) {
                foreach ($row as $key => $value) {
                    $row[$key] = $this->htmlEscape($value);
                }
                $row['_id'] = $rowId;

                foreach (Mage::getModel('sales/order_config')->getStatuses() as $key => $value)
                {
                    if($key == $row['status'])
                        $row['status_'.$key] = $this->_selected;
                }
                foreach (Mage::getModel('sales/order_config')->getStates() as $key => $value)
                {
                    if($key == $row['state'])
                        $row['state_'.$key] = $this->_selected;
                }
                $result[$rowId] = new Varien_Object($row);
                $this->_prepareArrayRow($result[$rowId]);
            }
        }
        $this->_arrayRowsCache = $result;
        return $this->_arrayRowsCache;
    }
}