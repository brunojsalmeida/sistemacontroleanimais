<?php
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 *                                   ATTENTION!
 * If you see this message in your browser (Internet Explorer, Mozilla Firefox, Google Chrome, etc.)
 * this means that PHP is not properly installed on your web server. Please refer to the PHP manual
 * for more details: http://php.net/manual/install.php 
 *
 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 */

    include_once dirname(__FILE__) . '/components/startup.php';
    include_once dirname(__FILE__) . '/components/application.php';
    include_once dirname(__FILE__) . '/' . 'authorization.php';


    include_once dirname(__FILE__) . '/' . 'database_engine/mysql_engine.php';
    include_once dirname(__FILE__) . '/' . 'components/page/page_includes.php';

    function GetConnectionOptions()
    {
        $result = GetGlobalConnectionOptions();
        $result['client_encoding'] = 'utf8';
        GetApplication()->GetUserAuthentication()->applyIdentityToConnectionOptions($result);
        return $result;
    }

    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class vacina_animalPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Animal');
            $this->SetMenuLabel('Animal');
    
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`animal`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id_animal', true, true, true),
                    new StringField('cpf_proprietario', true),
                    new StringField('nome_proprietario', true),
                    new StringField('nome_animal', true),
                    new StringField('especie', true),
                    new StringField('raca', true),
                    new StringField('sexo', true),
                    new StringField('porte', true),
                    new DateField('data_nascimento', true),
                    new IntegerField('id_campanha', true),
                    new IntegerField('id_vacina', true),
                    new DateField('data_aplicacao', true)
                )
            );
            $this->dataset->AddLookupField('cpf_proprietario', 'proprietario', new StringField('cpf_proprietario'), new StringField('nome', false, false, false, false, 'cpf_proprietario_nome', 'cpf_proprietario_nome_proprietario'), 'cpf_proprietario_nome_proprietario');
            $this->dataset->AddLookupField('id_campanha', 'campanha_vacinacao', new IntegerField('id_campanha'), new StringField('nome_campanha', false, false, false, false, 'id_campanha_nome_campanha', 'id_campanha_nome_campanha_campanha_vacinacao'), 'id_campanha_nome_campanha_campanha_vacinacao');
            $this->dataset->AddLookupField('id_vacina', 'vacina', new IntegerField('id_vacina'), new StringField('nome_vacina', false, false, false, false, 'id_vacina_nome_vacina', 'id_vacina_nome_vacina_vacina'), 'id_vacina_nome_vacina_vacina');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id_animal', 'id_animal', 'Id Animal'),
                new FilterColumn($this->dataset, 'cpf_proprietario', 'cpf_proprietario_nome', 'Cpf Proprietario'),
                new FilterColumn($this->dataset, 'nome_proprietario', 'nome_proprietario', 'Nome Proprietario'),
                new FilterColumn($this->dataset, 'nome_animal', 'nome_animal', 'Nome Animal'),
                new FilterColumn($this->dataset, 'especie', 'especie', 'Especie'),
                new FilterColumn($this->dataset, 'raca', 'raca', 'Raca'),
                new FilterColumn($this->dataset, 'sexo', 'sexo', 'Sexo'),
                new FilterColumn($this->dataset, 'porte', 'porte', 'Porte'),
                new FilterColumn($this->dataset, 'data_nascimento', 'data_nascimento', 'Data Nascimento'),
                new FilterColumn($this->dataset, 'id_campanha', 'id_campanha_nome_campanha', 'Id Campanha'),
                new FilterColumn($this->dataset, 'id_vacina', 'id_vacina_nome_vacina', 'Id Vacina'),
                new FilterColumn($this->dataset, 'data_aplicacao', 'data_aplicacao', 'Data Aplicacao')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id_animal'])
                ->addColumn($columns['cpf_proprietario'])
                ->addColumn($columns['nome_proprietario'])
                ->addColumn($columns['nome_animal'])
                ->addColumn($columns['especie'])
                ->addColumn($columns['raca'])
                ->addColumn($columns['sexo'])
                ->addColumn($columns['porte'])
                ->addColumn($columns['data_nascimento'])
                ->addColumn($columns['id_campanha'])
                ->addColumn($columns['id_vacina'])
                ->addColumn($columns['data_aplicacao']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('cpf_proprietario')
                ->setOptionsFor('data_nascimento')
                ->setOptionsFor('id_campanha')
                ->setOptionsFor('id_vacina')
                ->setOptionsFor('data_aplicacao');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_animal_edit');
            
            $filterBuilder->addColumn(
                $columns['id_animal'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('cpf_proprietario_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_vacina_animal_cpf_proprietario_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('cpf_proprietario', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_vacina_animal_cpf_proprietario_search');
            
            $text_editor = new TextEdit('cpf_proprietario');
            
            $filterBuilder->addColumn(
                $columns['cpf_proprietario'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('nome_proprietario_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['nome_proprietario'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('nome_animal_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['nome_animal'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('especie_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['especie'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('raca_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['raca'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('sexo_edit');
            $main_editor->SetMaxLength(5);
            
            $filterBuilder->addColumn(
                $columns['sexo'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('porte_edit');
            $main_editor->SetMaxLength(20);
            
            $filterBuilder->addColumn(
                $columns['porte'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            
            $filterBuilder->addColumn(
                $columns['data_nascimento'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('id_campanha_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_vacina_animal_id_campanha_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_campanha', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_vacina_animal_id_campanha_search');
            
            $text_editor = new TextEdit('id_campanha');
            
            $filterBuilder->addColumn(
                $columns['id_campanha'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('id_vacina_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_vacina_animal_id_vacina_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_vacina', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_vacina_animal_id_vacina_search');
            
            $text_editor = new TextEdit('id_vacina');
            
            $filterBuilder->addColumn(
                $columns['id_vacina'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            
            $filterBuilder->addColumn(
                $columns['data_aplicacao'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id_animal field
            //
            $column = new NumberViewColumn('id_animal', 'id_animal', 'Id Animal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_nome', 'Cpf Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome Animal', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for especie field
            //
            $column = new TextViewColumn('especie', 'especie', 'Especie', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for raca field
            //
            $column = new TextViewColumn('raca', 'raca', 'Raca', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for porte field
            //
            $column = new TextViewColumn('porte', 'porte', 'Porte', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for data_nascimento field
            //
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data Aplicacao', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id_animal field
            //
            $column = new NumberViewColumn('id_animal', 'id_animal', 'Id Animal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_nome', 'Cpf Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome Animal', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for especie field
            //
            $column = new TextViewColumn('especie', 'especie', 'Especie', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for raca field
            //
            $column = new TextViewColumn('raca', 'raca', 'Raca', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for porte field
            //
            $column = new TextViewColumn('porte', 'porte', 'Porte', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for data_nascimento field
            //
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data Aplicacao', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for cpf_proprietario field
            //
            $editor = new DynamicCombobox('cpf_proprietario_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Cpf Proprietario', 'cpf_proprietario', 'cpf_proprietario_nome', 'edit_vacina_animal_cpf_proprietario_search', $editor, $this->dataset, $lookupDataset, 'cpf_proprietario', 'nome', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nome_proprietario field
            //
            $editor = new TextEdit('nome_proprietario_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Proprietario', 'nome_proprietario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nome_animal field
            //
            $editor = new TextEdit('nome_animal_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Animal', 'nome_animal', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for especie field
            //
            $editor = new TextEdit('especie_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Especie', 'especie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for raca field
            //
            $editor = new TextEdit('raca_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Raca', 'raca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new TextEdit('sexo_edit');
            $editor->SetMaxLength(5);
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for porte field
            //
            $editor = new TextEdit('porte_edit');
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Porte', 'porte', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for data_nascimento field
            //
            $editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Nascimento', 'data_nascimento', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_campanha field
            //
            $editor = new DynamicCombobox('id_campanha_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Campanha', 'id_campanha', 'id_campanha_nome_campanha', 'edit_vacina_animal_id_campanha_search', $editor, $this->dataset, $lookupDataset, 'id_campanha', 'nome_campanha', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for id_vacina field
            //
            $editor = new DynamicCombobox('id_vacina_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Vacina', 'id_vacina', 'id_vacina_nome_vacina', 'edit_vacina_animal_id_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for data_aplicacao field
            //
            $editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Aplicacao', 'data_aplicacao', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for cpf_proprietario field
            //
            $editor = new DynamicCombobox('cpf_proprietario_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Cpf Proprietario', 'cpf_proprietario', 'cpf_proprietario_nome', 'multi_edit_vacina_animal_cpf_proprietario_search', $editor, $this->dataset, $lookupDataset, 'cpf_proprietario', 'nome', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for nome_proprietario field
            //
            $editor = new TextEdit('nome_proprietario_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Proprietario', 'nome_proprietario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for nome_animal field
            //
            $editor = new TextEdit('nome_animal_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Animal', 'nome_animal', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for especie field
            //
            $editor = new TextEdit('especie_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Especie', 'especie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for raca field
            //
            $editor = new TextEdit('raca_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Raca', 'raca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new TextEdit('sexo_edit');
            $editor->SetMaxLength(5);
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for porte field
            //
            $editor = new TextEdit('porte_edit');
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Porte', 'porte', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for data_nascimento field
            //
            $editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Nascimento', 'data_nascimento', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_campanha field
            //
            $editor = new DynamicCombobox('id_campanha_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Campanha', 'id_campanha', 'id_campanha_nome_campanha', 'multi_edit_vacina_animal_id_campanha_search', $editor, $this->dataset, $lookupDataset, 'id_campanha', 'nome_campanha', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for id_vacina field
            //
            $editor = new DynamicCombobox('id_vacina_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Vacina', 'id_vacina', 'id_vacina_nome_vacina', 'multi_edit_vacina_animal_id_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for data_aplicacao field
            //
            $editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Aplicacao', 'data_aplicacao', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for cpf_proprietario field
            //
            $editor = new DynamicCombobox('cpf_proprietario_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Cpf Proprietario', 'cpf_proprietario', 'cpf_proprietario_nome', 'insert_vacina_animal_cpf_proprietario_search', $editor, $this->dataset, $lookupDataset, 'cpf_proprietario', 'nome', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nome_proprietario field
            //
            $editor = new TextEdit('nome_proprietario_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Proprietario', 'nome_proprietario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nome_animal field
            //
            $editor = new TextEdit('nome_animal_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Animal', 'nome_animal', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for especie field
            //
            $editor = new TextEdit('especie_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Especie', 'especie', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for raca field
            //
            $editor = new TextEdit('raca_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Raca', 'raca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new TextEdit('sexo_edit');
            $editor->SetMaxLength(5);
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for porte field
            //
            $editor = new TextEdit('porte_edit');
            $editor->SetMaxLength(20);
            $editColumn = new CustomEditColumn('Porte', 'porte', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for data_nascimento field
            //
            $editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Nascimento', 'data_nascimento', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_campanha field
            //
            $editor = new DynamicCombobox('id_campanha_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Campanha', 'id_campanha', 'id_campanha_nome_campanha', 'insert_vacina_animal_id_campanha_search', $editor, $this->dataset, $lookupDataset, 'id_campanha', 'nome_campanha', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for id_vacina field
            //
            $editor = new DynamicCombobox('id_vacina_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Id Vacina', 'id_vacina', 'id_vacina_nome_vacina', 'insert_vacina_animal_id_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for data_aplicacao field
            //
            $editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Aplicacao', 'data_aplicacao', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id_animal field
            //
            $column = new NumberViewColumn('id_animal', 'id_animal', 'Id Animal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_nome', 'Cpf Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome Animal', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for especie field
            //
            $column = new TextViewColumn('especie', 'especie', 'Especie', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for raca field
            //
            $column = new TextViewColumn('raca', 'raca', 'Raca', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for porte field
            //
            $column = new TextViewColumn('porte', 'porte', 'Porte', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for data_nascimento field
            //
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data Aplicacao', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_animal field
            //
            $column = new NumberViewColumn('id_animal', 'id_animal', 'Id Animal', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for nome field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_nome', 'Cpf Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome Animal', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for especie field
            //
            $column = new TextViewColumn('especie', 'especie', 'Especie', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for raca field
            //
            $column = new TextViewColumn('raca', 'raca', 'Raca', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for porte field
            //
            $column = new TextViewColumn('porte', 'porte', 'Porte', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for data_nascimento field
            //
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data Aplicacao', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for nome field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_nome', 'Cpf Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome Animal', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for especie field
            //
            $column = new TextViewColumn('especie', 'especie', 'Especie', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for raca field
            //
            $column = new TextViewColumn('raca', 'raca', 'Raca', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for porte field
            //
            $column = new TextViewColumn('porte', 'porte', 'Porte', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for data_nascimento field
            //
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data Aplicacao', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && true);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setAllowPrintSelectedRecords(true);
            $this->setExportListAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_vacina_animal_cpf_proprietario_search', 'cpf_proprietario', 'nome', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_vacina_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_vacina_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_vacina_animal_cpf_proprietario_search', 'cpf_proprietario', 'nome', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_vacina_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_vacina_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_vacina_animal_cpf_proprietario_search', 'cpf_proprietario', 'nome', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_vacina_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_vacina_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`proprietario`');
            $lookupDataset->addFields(
                array(
                    new StringField('cpf_proprietario', true, true),
                    new StringField('nome', true),
                    new StringField('rua', true),
                    new IntegerField('numero', true),
                    new StringField('bairro', true),
                    new StringField('municipio', true),
                    new StringField('contato_principal', true),
                    new StringField('contato_secundario')
                )
            );
            $lookupDataset->setOrderByField('nome', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_vacina_animal_cpf_proprietario_search', 'cpf_proprietario', 'nome', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $lookupDataset->setOrderByField('nome_campanha', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_vacina_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_vacina_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }
    
    
    
    
    // OnBeforePageExecute event handler
    
    
    
    class vacina_campanha_vacinacaoPage extends DetailPage
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Campanha Vacinacao');
            $this->SetMenuLabel('Campanha Vacinacao');
    
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`campanha_vacinacao`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id_campanha', true, true, true),
                    new StringField('nome_campanha', true),
                    new IntegerField('vacina', true),
                    new DateField('data_inicio', true),
                    new DateField('data_fim'),
                    new StringField('orgao_responsavel')
                )
            );
            $this->dataset->AddLookupField('vacina', 'vacina', new IntegerField('id_vacina'), new StringField('nome_vacina', false, false, false, false, 'vacina_nome_vacina', 'vacina_nome_vacina_vacina'), 'vacina_nome_vacina_vacina');
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id_campanha', 'id_campanha', 'Id Campanha'),
                new FilterColumn($this->dataset, 'nome_campanha', 'nome_campanha', 'Nome Campanha'),
                new FilterColumn($this->dataset, 'vacina', 'vacina_nome_vacina', 'Vacina'),
                new FilterColumn($this->dataset, 'data_inicio', 'data_inicio', 'Data Inicio'),
                new FilterColumn($this->dataset, 'data_fim', 'data_fim', 'Data Fim'),
                new FilterColumn($this->dataset, 'orgao_responsavel', 'orgao_responsavel', 'Orgao Responsavel')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id_campanha'])
                ->addColumn($columns['nome_campanha'])
                ->addColumn($columns['vacina'])
                ->addColumn($columns['data_inicio'])
                ->addColumn($columns['data_fim'])
                ->addColumn($columns['orgao_responsavel']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('vacina')
                ->setOptionsFor('data_inicio')
                ->setOptionsFor('data_fim');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_campanha_edit');
            
            $filterBuilder->addColumn(
                $columns['id_campanha'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('nome_campanha_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['nome_campanha'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DynamicCombobox('vacina_edit', $this->CreateLinkBuilder());
            $main_editor->setAllowClear(true);
            $main_editor->setMinimumInputLength(0);
            $main_editor->SetAllowNullValue(false);
            $main_editor->SetHandlerName('filter_builder_vacina_campanha_vacinacao_vacina_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('vacina', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_vacina_campanha_vacinacao_vacina_search');
            
            $text_editor = new TextEdit('vacina');
            
            $filterBuilder->addColumn(
                $columns['vacina'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $text_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $text_editor,
                    FilterConditionOperator::BEGINS_WITH => $text_editor,
                    FilterConditionOperator::ENDS_WITH => $text_editor,
                    FilterConditionOperator::IS_LIKE => $text_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $text_editor,
                    FilterConditionOperator::IN => $multi_value_select_editor,
                    FilterConditionOperator::NOT_IN => $multi_value_select_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('data_inicio_edit', false, 'd-m-Y');
            
            $filterBuilder->addColumn(
                $columns['data_inicio'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('data_fim_edit', false, 'd-m-Y');
            
            $filterBuilder->addColumn(
                $columns['data_fim'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('orgao_responsavel_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['orgao_responsavel'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            //
            // View column for id_campanha field
            //
            $column = new NumberViewColumn('id_campanha', 'id_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('nome_campanha', 'nome_campanha', 'Nome Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('vacina', 'vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for data_inicio field
            //
            $column = new DateTimeViewColumn('data_inicio', 'data_inicio', 'Data Inicio', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for data_fim field
            //
            $column = new DateTimeViewColumn('data_fim', 'data_fim', 'Data Fim', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for orgao_responsavel field
            //
            $column = new TextViewColumn('orgao_responsavel', 'orgao_responsavel', 'Orgao Responsavel', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id_campanha field
            //
            $column = new NumberViewColumn('id_campanha', 'id_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('nome_campanha', 'nome_campanha', 'Nome Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('vacina', 'vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for data_inicio field
            //
            $column = new DateTimeViewColumn('data_inicio', 'data_inicio', 'Data Inicio', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for data_fim field
            //
            $column = new DateTimeViewColumn('data_fim', 'data_fim', 'Data Fim', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for orgao_responsavel field
            //
            $column = new TextViewColumn('orgao_responsavel', 'orgao_responsavel', 'Orgao Responsavel', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for nome_campanha field
            //
            $editor = new TextEdit('nome_campanha_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Campanha', 'nome_campanha', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for vacina field
            //
            $editor = new DynamicCombobox('vacina_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Vacina', 'vacina', 'vacina_nome_vacina', 'edit_vacina_campanha_vacinacao_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for data_inicio field
            //
            $editor = new DateTimeEdit('data_inicio_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Inicio', 'data_inicio', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for data_fim field
            //
            $editor = new DateTimeEdit('data_fim_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Fim', 'data_fim', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for orgao_responsavel field
            //
            $editor = new TextEdit('orgao_responsavel_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Orgao Responsavel', 'orgao_responsavel', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for nome_campanha field
            //
            $editor = new TextEdit('nome_campanha_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Campanha', 'nome_campanha', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for vacina field
            //
            $editor = new DynamicCombobox('vacina_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Vacina', 'vacina', 'vacina_nome_vacina', 'multi_edit_vacina_campanha_vacinacao_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for data_inicio field
            //
            $editor = new DateTimeEdit('data_inicio_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Inicio', 'data_inicio', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for data_fim field
            //
            $editor = new DateTimeEdit('data_fim_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Fim', 'data_fim', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for orgao_responsavel field
            //
            $editor = new TextEdit('orgao_responsavel_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Orgao Responsavel', 'orgao_responsavel', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nome_campanha field
            //
            $editor = new TextEdit('nome_campanha_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome Campanha', 'nome_campanha', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for vacina field
            //
            $editor = new DynamicCombobox('vacina_edit', $this->CreateLinkBuilder());
            $editor->setAllowClear(true);
            $editor->setMinimumInputLength(0);
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $editColumn = new DynamicLookupEditColumn('Vacina', 'vacina', 'vacina_nome_vacina', 'insert_vacina_campanha_vacinacao_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for data_inicio field
            //
            $editor = new DateTimeEdit('data_inicio_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Inicio', 'data_inicio', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for data_fim field
            //
            $editor = new DateTimeEdit('data_fim_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data Fim', 'data_fim', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for orgao_responsavel field
            //
            $editor = new TextEdit('orgao_responsavel_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Orgao Responsavel', 'orgao_responsavel', $editor, $this->dataset);
            $editColumn->SetAllowSetToNull(true);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id_campanha field
            //
            $column = new NumberViewColumn('id_campanha', 'id_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('nome_campanha', 'nome_campanha', 'Nome Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('vacina', 'vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for data_inicio field
            //
            $column = new DateTimeViewColumn('data_inicio', 'data_inicio', 'Data Inicio', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddPrintColumn($column);
            
            //
            // View column for data_fim field
            //
            $column = new DateTimeViewColumn('data_fim', 'data_fim', 'Data Fim', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddPrintColumn($column);
            
            //
            // View column for orgao_responsavel field
            //
            $column = new TextViewColumn('orgao_responsavel', 'orgao_responsavel', 'Orgao Responsavel', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_campanha field
            //
            $column = new NumberViewColumn('id_campanha', 'id_campanha', 'Id Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('nome_campanha', 'nome_campanha', 'Nome Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('vacina', 'vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for data_inicio field
            //
            $column = new DateTimeViewColumn('data_inicio', 'data_inicio', 'Data Inicio', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
            
            //
            // View column for data_fim field
            //
            $column = new DateTimeViewColumn('data_fim', 'data_fim', 'Data Fim', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
            
            //
            // View column for orgao_responsavel field
            //
            $column = new TextViewColumn('orgao_responsavel', 'orgao_responsavel', 'Orgao Responsavel', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('nome_campanha', 'nome_campanha', 'Nome Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('vacina', 'vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for data_inicio field
            //
            $column = new DateTimeViewColumn('data_inicio', 'data_inicio', 'Data Inicio', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for data_fim field
            //
            $column = new DateTimeViewColumn('data_fim', 'data_fim', 'Data Fim', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for orgao_responsavel field
            //
            $column = new TextViewColumn('orgao_responsavel', 'orgao_responsavel', 'Orgao Responsavel', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && true);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setAllowPrintSelectedRecords(true);
            $this->setExportListAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_vacina_campanha_vacinacao_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_vacina_campanha_vacinacao_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_vacina_campanha_vacinacao_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $lookupDataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $lookupDataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
            $lookupDataset->setOrderByField('nome_vacina', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_vacina_campanha_vacinacao_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
            GetApplication()->RegisterHTTPHandler($handler);
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }
    
    // OnBeforePageExecute event handler
    
    
    
    class vacinaPage extends Page
    {
        protected function DoBeforeCreate()
        {
            $this->SetTitle('Vacina');
            $this->SetMenuLabel('Vacina');
    
            $this->dataset = new TableDataset(
                MyPDOConnectionFactory::getInstance(),
                GetConnectionOptions(),
                '`vacina`');
            $this->dataset->addFields(
                array(
                    new IntegerField('id_vacina', true, true, true),
                    new StringField('nome_vacina', true),
                    new StringField('fabricante', true),
                    new StringField('lote_fabricacao', true),
                    new DateField('validade', true)
                )
            );
        }
    
        protected function DoPrepare() {
    
        }
    
        protected function CreatePageNavigator()
        {
            $result = new CompositePageNavigator($this);
            
            $partitionNavigator = new PageNavigator('pnav', $this, $this->dataset);
            $partitionNavigator->SetRowsPerPage(20);
            $result->AddPageNavigator($partitionNavigator);
            
            return $result;
        }
    
        protected function CreateRssGenerator()
        {
            return null;
        }
    
        protected function setupCharts()
        {
    
        }
    
        protected function getFiltersColumns()
        {
            return array(
                new FilterColumn($this->dataset, 'id_vacina', 'id_vacina', 'Id Vacina'),
                new FilterColumn($this->dataset, 'nome_vacina', 'nome_vacina', 'Nome da Vacina'),
                new FilterColumn($this->dataset, 'fabricante', 'fabricante', 'Fabricante'),
                new FilterColumn($this->dataset, 'lote_fabricacao', 'lote_fabricacao', 'Lote de Fabricação'),
                new FilterColumn($this->dataset, 'validade', 'validade', 'Validade')
            );
        }
    
        protected function setupQuickFilter(QuickFilter $quickFilter, FixedKeysArray $columns)
        {
            $quickFilter
                ->addColumn($columns['id_vacina'])
                ->addColumn($columns['nome_vacina'])
                ->addColumn($columns['fabricante'])
                ->addColumn($columns['lote_fabricacao'])
                ->addColumn($columns['validade']);
        }
    
        protected function setupColumnFilter(ColumnFilter $columnFilter)
        {
            $columnFilter
                ->setOptionsFor('validade');
        }
    
        protected function setupFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
            $main_editor = new TextEdit('id_vacina_edit');
            
            $filterBuilder->addColumn(
                $columns['id_vacina'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('nome_vacina_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['nome_vacina'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('fabricante_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['fabricante'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new TextEdit('lote_fabricacao_edit');
            $main_editor->SetMaxLength(45);
            
            $filterBuilder->addColumn(
                $columns['lote_fabricacao'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::CONTAINS => $main_editor,
                    FilterConditionOperator::DOES_NOT_CONTAIN => $main_editor,
                    FilterConditionOperator::BEGINS_WITH => $main_editor,
                    FilterConditionOperator::ENDS_WITH => $main_editor,
                    FilterConditionOperator::IS_LIKE => $main_editor,
                    FilterConditionOperator::IS_NOT_LIKE => $main_editor,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
            
            $main_editor = new DateTimeEdit('validade_edit', false, 'd-m-Y');
            
            $filterBuilder->addColumn(
                $columns['validade'],
                array(
                    FilterConditionOperator::EQUALS => $main_editor,
                    FilterConditionOperator::DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN => $main_editor,
                    FilterConditionOperator::IS_GREATER_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN => $main_editor,
                    FilterConditionOperator::IS_LESS_THAN_OR_EQUAL_TO => $main_editor,
                    FilterConditionOperator::IS_BETWEEN => $main_editor,
                    FilterConditionOperator::IS_NOT_BETWEEN => $main_editor,
                    FilterConditionOperator::DATE_EQUALS => $main_editor,
                    FilterConditionOperator::DATE_DOES_NOT_EQUAL => $main_editor,
                    FilterConditionOperator::TODAY => null,
                    FilterConditionOperator::IS_BLANK => null,
                    FilterConditionOperator::IS_NOT_BLANK => null
                )
            );
        }
    
        protected function AddOperationsColumns(Grid $grid)
        {
            $actions = $grid->getActions();
            $actions->setCaption($this->GetLocalizerCaptions()->GetMessageString('Actions'));
            $actions->setPosition(ActionList::POSITION_LEFT);
            
            if ($this->GetSecurityInfo()->HasViewGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('View'), OPERATION_VIEW, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
            
            if ($this->GetSecurityInfo()->HasEditGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Edit'), OPERATION_EDIT, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowEditButtonHandler', $this);
            }
            
            if ($this->GetSecurityInfo()->HasDeleteGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Delete'), OPERATION_DELETE, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
                $operation->OnShow->AddListener('ShowDeleteButtonHandler', $this);
                $operation->SetAdditionalAttribute('data-modal-operation', 'delete');
                $operation->SetAdditionalAttribute('data-delete-handler-name', $this->GetModalGridDeleteHandler());
            }
            
            if ($this->GetSecurityInfo()->HasAddGrant())
            {
                $operation = new LinkOperation($this->GetLocalizerCaptions()->GetMessageString('Copy'), OPERATION_COPY, $this->dataset, $grid);
                $operation->setUseImage(true);
                $actions->addOperation($operation);
            }
        }
    
        protected function AddFieldColumns(Grid $grid, $withDetails = true)
        {
            if (GetCurrentUserPermissionsForPage('vacina.animal')->HasViewGrant() && $withDetails)
            {
            //
            // View column for vacina_animal detail
            //
            $column = new DetailColumn(array('id_vacina'), 'vacina.animal', 'vacina_animal_handler', $this->dataset, 'Animal');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            if (GetCurrentUserPermissionsForPage('vacina.campanha_vacinacao')->HasViewGrant() && $withDetails)
            {
            //
            // View column for vacina_campanha_vacinacao detail
            //
            $column = new DetailColumn(array('id_vacina'), 'vacina.campanha_vacinacao', 'vacina_campanha_vacinacao_handler', $this->dataset, 'Campanha Vacinacao');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $grid->AddViewColumn($column);
            }
            
            //
            // View column for id_vacina field
            //
            $column = new NumberViewColumn('id_vacina', 'id_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('nome_vacina', 'nome_vacina', 'Nome da Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for fabricante field
            //
            $column = new TextViewColumn('fabricante', 'fabricante', 'Fabricante', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for lote_fabricacao field
            //
            $column = new TextViewColumn('lote_fabricacao', 'lote_fabricacao', 'Lote de Fabricação', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for validade field
            //
            $column = new DateTimeViewColumn('validade', 'validade', 'Validade', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
        }
    
        protected function AddSingleRecordViewColumns(Grid $grid)
        {
            //
            // View column for id_vacina field
            //
            $column = new NumberViewColumn('id_vacina', 'id_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('nome_vacina', 'nome_vacina', 'Nome da Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for fabricante field
            //
            $column = new TextViewColumn('fabricante', 'fabricante', 'Fabricante', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for lote_fabricacao field
            //
            $column = new TextViewColumn('lote_fabricacao', 'lote_fabricacao', 'Lote de Fabricação', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for validade field
            //
            $column = new DateTimeViewColumn('validade', 'validade', 'Validade', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddSingleRecordViewColumn($column);
        }
    
        protected function AddEditColumns(Grid $grid)
        {
            //
            // Edit column for nome_vacina field
            //
            $editor = new TextEdit('nome_vacina_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome da Vacina', 'nome_vacina', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for fabricante field
            //
            $editor = new TextEdit('fabricante_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Fabricante', 'fabricante', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for lote_fabricacao field
            //
            $editor = new TextEdit('lote_fabricacao_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Lote de Fabricação', 'lote_fabricacao', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for validade field
            //
            $editor = new DateTimeEdit('validade_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Validade', 'validade', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
        }
    
        protected function AddMultiEditColumns(Grid $grid)
        {
            //
            // Edit column for nome_vacina field
            //
            $editor = new TextEdit('nome_vacina_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome da Vacina', 'nome_vacina', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for fabricante field
            //
            $editor = new TextEdit('fabricante_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Fabricante', 'fabricante', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for lote_fabricacao field
            //
            $editor = new TextEdit('lote_fabricacao_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Lote de Fabricação', 'lote_fabricacao', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for validade field
            //
            $editor = new DateTimeEdit('validade_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Validade', 'validade', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
        }
    
        protected function AddInsertColumns(Grid $grid)
        {
            //
            // Edit column for nome_vacina field
            //
            $editor = new TextEdit('nome_vacina_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome da Vacina', 'nome_vacina', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for fabricante field
            //
            $editor = new TextEdit('fabricante_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Fabricante', 'fabricante', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for lote_fabricacao field
            //
            $editor = new TextEdit('lote_fabricacao_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Lote de Fabricação', 'lote_fabricacao', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for validade field
            //
            $editor = new DateTimeEdit('validade_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Validade', 'validade', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            $grid->SetShowAddButton(true && $this->GetSecurityInfo()->HasAddGrant());
        }
    
        private function AddMultiUploadColumn(Grid $grid)
        {
    
        }
    
        protected function AddPrintColumns(Grid $grid)
        {
            //
            // View column for id_vacina field
            //
            $column = new NumberViewColumn('id_vacina', 'id_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('nome_vacina', 'nome_vacina', 'Nome da Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for fabricante field
            //
            $column = new TextViewColumn('fabricante', 'fabricante', 'Fabricante', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for lote_fabricacao field
            //
            $column = new TextViewColumn('lote_fabricacao', 'lote_fabricacao', 'Lote de Fabricação', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for validade field
            //
            $column = new DateTimeViewColumn('validade', 'validade', 'Validade', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddPrintColumn($column);
        }
    
        protected function AddExportColumns(Grid $grid)
        {
            //
            // View column for id_vacina field
            //
            $column = new NumberViewColumn('id_vacina', 'id_vacina', 'Id Vacina', $this->dataset);
            $column->SetOrderable(true);
            $column->setNumberAfterDecimal(0);
            $column->setThousandsSeparator(',');
            $column->setDecimalSeparator('');
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('nome_vacina', 'nome_vacina', 'Nome da Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for fabricante field
            //
            $column = new TextViewColumn('fabricante', 'fabricante', 'Fabricante', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for lote_fabricacao field
            //
            $column = new TextViewColumn('lote_fabricacao', 'lote_fabricacao', 'Lote de Fabricação', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for validade field
            //
            $column = new DateTimeViewColumn('validade', 'validade', 'Validade', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('nome_vacina', 'nome_vacina', 'Nome da Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for fabricante field
            //
            $column = new TextViewColumn('fabricante', 'fabricante', 'Fabricante', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for lote_fabricacao field
            //
            $column = new TextViewColumn('lote_fabricacao', 'lote_fabricacao', 'Lote de Fabricação', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for validade field
            //
            $column = new DateTimeViewColumn('validade', 'validade', 'Validade', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
        }
    
        private function AddCompareHeaderColumns(Grid $grid)
        {
    
        }
    
        public function GetPageDirection()
        {
            return null;
        }
    
        public function isFilterConditionRequired()
        {
            return false;
        }
    
        protected function ApplyCommonColumnEditProperties(CustomEditColumn $column)
        {
            $column->SetDisplaySetToNullCheckBox(false);
            $column->SetDisplaySetToDefaultCheckBox(false);
    		$column->SetVariableContainer($this->GetColumnVariableContainer());
        }
    
        function CreateMasterDetailRecordGrid()
        {
            $result = new Grid($this, $this->dataset);
            
            $this->AddFieldColumns($result, false);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            
            $result->SetAllowDeleteSelected(false);
            $result->SetShowUpdateLink(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(false);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $this->setupGridColumnGroup($result);
            $this->attachGridEventHandlers($result);
            
            return $result;
        }
        
        function GetCustomClientScript()
        {
            return ;
        }
        
        function GetOnPageLoadedClientScript()
        {
            return ;
        }
        protected function GetEnableModalGridDelete() { return true; }
    
        protected function CreateGrid()
        {
            $result = new Grid($this, $this->dataset);
            if ($this->GetSecurityInfo()->HasDeleteGrant())
               $result->SetAllowDeleteSelected(true);
            else
               $result->SetAllowDeleteSelected(false);   
            
            ApplyCommonPageSettings($this, $result);
            
            $result->SetUseImagesForActions(true);
            $result->SetUseFixedHeader(false);
            $result->SetShowLineNumbers(false);
            $result->SetShowKeyColumnsImagesInHeader(false);
            $result->SetViewMode(ViewMode::TABLE);
            $result->setEnableRuntimeCustomization(true);
            $result->setAllowCompare(true);
            $this->AddCompareHeaderColumns($result);
            $this->AddCompareColumns($result);
            $result->setMultiEditAllowed($this->GetSecurityInfo()->HasEditGrant() && true);
            $result->setTableBordered(false);
            $result->setTableCondensed(false);
            
            $result->SetHighlightRowAtHover(false);
            $result->SetWidth('');
            $this->AddOperationsColumns($result);
            $this->AddFieldColumns($result);
            $this->AddSingleRecordViewColumns($result);
            $this->AddEditColumns($result);
            $this->AddMultiEditColumns($result);
            $this->AddInsertColumns($result);
            $this->AddPrintColumns($result);
            $this->AddExportColumns($result);
            $this->AddMultiUploadColumn($result);
    
    
            $this->SetShowPageList(true);
            $this->SetShowTopPageNavigator(true);
            $this->SetShowBottomPageNavigator(true);
            $this->setPrintListAvailable(true);
            $this->setPrintListRecordAvailable(false);
            $this->setPrintOneRecordAvailable(true);
            $this->setAllowPrintSelectedRecords(true);
            $this->setExportListAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportSelectedRecordsAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
            $this->setExportListRecordAvailable(array());
            $this->setExportOneRecordAvailable(array('pdf', 'excel', 'word', 'xml', 'csv'));
    
            return $result;
        }
     
        protected function setClientSideEvents(Grid $grid) {
    
        }
    
        protected function doRegisterHandlers() {
            $detailPage = new vacina_animalPage('vacina_animal', $this, array('id_vacina'), array('id_vacina'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionsForPage('vacina.animal'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('vacina.animal'));
            $detailPage->SetHttpHandlerName('vacina_animal_handler');
            $handler = new PageHTTPHandler('vacina_animal_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
            $detailPage = new vacina_campanha_vacinacaoPage('vacina_campanha_vacinacao', $this, array('vacina'), array('id_vacina'), $this->GetForeignKeyFields(), $this->CreateMasterDetailRecordGrid(), $this->dataset, GetCurrentUserPermissionsForPage('vacina.campanha_vacinacao'), 'UTF-8');
            $detailPage->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource('vacina.campanha_vacinacao'));
            $detailPage->SetHttpHandlerName('vacina_campanha_vacinacao_handler');
            $handler = new PageHTTPHandler('vacina_campanha_vacinacao_handler', $detailPage);
            GetApplication()->RegisterHTTPHandler($handler);
            
        }
       
        protected function doCustomRenderColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderPrintColumn($fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomRenderExportColumn($exportType, $fieldName, $fieldData, $rowData, &$customText, &$handled)
        { 
    
        }
    
        protected function doCustomDrawRow($rowData, &$cellFontColor, &$cellFontSize, &$cellBgColor, &$cellItalicAttr, &$cellBoldAttr)
        {
    
        }
    
        protected function doExtendedCustomDrawRow($rowData, &$rowCellStyles, &$rowStyles, &$rowClasses, &$cellClasses)
        {
    
        }
    
        protected function doCustomRenderTotal($totalValue, $aggregate, $columnName, &$customText, &$handled)
        {
    
        }
    
        protected function doCustomDefaultValues(&$values, &$handled) 
        {
    
        }
    
        protected function doCustomCompareColumn($columnName, $valueA, $valueB, &$result)
        {
    
        }
    
        protected function doBeforeInsertRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeUpdateRecord($page, $oldRowData, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doBeforeDeleteRecord($page, &$rowData, $tableName, &$cancel, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterInsertRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterUpdateRecord($page, $oldRowData, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doAfterDeleteRecord($page, $rowData, $tableName, &$success, &$message, &$messageDisplayTime)
        {
    
        }
    
        protected function doCustomHTMLHeader($page, &$customHtmlHeaderText)
        { 
    
        }
    
        protected function doGetCustomTemplate($type, $part, $mode, &$result, &$params)
        {
    
        }
    
        protected function doGetCustomExportOptions(Page $page, $exportType, $rowData, &$options)
        {
    
        }
    
        protected function doFileUpload($fieldName, $rowData, &$result, &$accept, $originalFileName, $originalFileExtension, $fileSize, $tempFileName)
        {
    
        }
    
        protected function doPrepareChart(Chart $chart)
        {
    
        }
    
        protected function doPrepareColumnFilter(ColumnFilter $columnFilter)
        {
    
        }
    
        protected function doPrepareFilterBuilder(FilterBuilder $filterBuilder, FixedKeysArray $columns)
        {
    
        }
    
        protected function doGetSelectionFilters(FixedKeysArray $columns, &$result)
        {
    
        }
    
        protected function doGetCustomFormLayout($mode, FixedKeysArray $columns, FormLayout $layout)
        {
    
        }
    
        protected function doGetCustomColumnGroup(FixedKeysArray $columns, ViewColumnGroup $columnGroup)
        {
    
        }
    
        protected function doPageLoaded()
        {
    
        }
    
        protected function doCalculateFields($rowData, $fieldName, &$value)
        {
    
        }
    
        protected function doGetCustomRecordPermissions(Page $page, &$usingCondition, $rowData, &$allowEdit, &$allowDelete, &$mergeWithDefault, &$handled)
        {
    
        }
    
        protected function doAddEnvironmentVariables(Page $page, &$variables)
        {
    
        }
    
    }

    SetUpUserAuthorization();

    try
    {
        $Page = new vacinaPage("vacina", "vacina.php", GetCurrentUserPermissionsForPage("vacina"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("vacina"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
