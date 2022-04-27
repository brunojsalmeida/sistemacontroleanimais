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
    
    
    
    class animalPage extends Page
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
            $this->dataset->AddLookupField('cpf_proprietario', 'proprietario', new StringField('cpf_proprietario'), new StringField('cpf_proprietario', false, false, false, false, 'cpf_proprietario_cpf_proprietario', 'cpf_proprietario_cpf_proprietario_proprietario'), 'cpf_proprietario_cpf_proprietario_proprietario');
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
                new FilterColumn($this->dataset, 'cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'CPF do Proprietario'),
                new FilterColumn($this->dataset, 'nome_proprietario', 'nome_proprietario', 'Nome do Proprietario'),
                new FilterColumn($this->dataset, 'nome_animal', 'nome_animal', 'Nome do Animal'),
                new FilterColumn($this->dataset, 'especie', 'especie', 'Especie'),
                new FilterColumn($this->dataset, 'raca', 'raca', 'Raça'),
                new FilterColumn($this->dataset, 'sexo', 'sexo', 'Sexo'),
                new FilterColumn($this->dataset, 'porte', 'porte', 'Porte'),
                new FilterColumn($this->dataset, 'data_nascimento', 'data_nascimento', 'Data do Nascimento'),
                new FilterColumn($this->dataset, 'id_campanha', 'id_campanha_nome_campanha', 'Campanha'),
                new FilterColumn($this->dataset, 'id_vacina', 'id_vacina_nome_vacina', 'Vacina'),
                new FilterColumn($this->dataset, 'data_aplicacao', 'data_aplicacao', 'Data de Aplicação')
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
            $main_editor->SetHandlerName('filter_builder_animal_cpf_proprietario_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('cpf_proprietario', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_animal_cpf_proprietario_search');
            
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
            
            $main_editor = new ComboBox('sexo');
            $main_editor->SetAllowNullValue(false);
            $main_editor->addChoice('FEMEA', 'FEMEA');
            $main_editor->addChoice('MACHO', 'MACHO');
            
            $multi_value_select_editor = new MultiValueSelect('sexo');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('sexo');
            
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
            
            $main_editor = new ComboBox('porte');
            $main_editor->SetAllowNullValue(false);
            $main_editor->addChoice('PEQUENO', 'Pequeno');
            $main_editor->addChoice('MEDIO', 'Médio');
            $main_editor->addChoice('GRANDE', 'Grande');
            
            $multi_value_select_editor = new MultiValueSelect('porte');
            $multi_value_select_editor->setChoices($main_editor->getChoices());
            
            $text_editor = new TextEdit('porte');
            
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
            $main_editor->SetHandlerName('filter_builder_animal_id_campanha_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_campanha', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_animal_id_campanha_search');
            
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
            $main_editor->SetHandlerName('filter_builder_animal_id_vacina_search');
            
            $multi_value_select_editor = new RemoteMultiValueSelect('id_vacina', $this->CreateLinkBuilder());
            $multi_value_select_editor->SetHandlerName('filter_builder_animal_id_vacina_search');
            
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
            // View column for cpf_proprietario field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'CPF do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome do Animal', $this->dataset);
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
            // View column for sexo field
            //
            $column = new TextViewColumn('sexo', 'sexo', 'Sexo', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for data_nascimento field
            //
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data do Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Campanha', $this->dataset);
            $column->SetOrderable(true);
            $column->setMinimalVisibility(ColumnVisibility::PHONE);
            $column->SetDescription('');
            $column->SetFixedWidth(null);
            $grid->AddViewColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data de Aplicação', $this->dataset);
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
            // View column for cpf_proprietario field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'CPF do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome do Animal', $this->dataset);
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
            $column = new TextViewColumn('raca', 'raca', 'Raça', $this->dataset);
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
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data do Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddSingleRecordViewColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data de Aplicação', $this->dataset);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $editColumn = new DynamicLookupEditColumn('CPF do Proprietario', 'cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'edit_animal_cpf_proprietario_search', $editor, $this->dataset, $lookupDataset, 'cpf_proprietario', 'cpf_proprietario', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nome_proprietario field
            //
            $editor = new TextEdit('nome_proprietario_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome do Proprietario', 'nome_proprietario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for nome_animal field
            //
            $editor = new TextEdit('nome_animal_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome do Animal', 'nome_animal', $editor, $this->dataset);
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
            $editColumn = new CustomEditColumn('Raça', 'raca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new RadioEdit('sexo_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice('FEMEA', 'FEMEA');
            $editor->addChoice('MACHO', 'MACHO');
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for porte field
            //
            $editor = new RadioEdit('porte_edit');
            $editor->SetDisplayMode(RadioEdit::StackedMode);
            $editor->addChoice('PEQUENO', 'Pequeno');
            $editor->addChoice('MEDIO', 'Médio');
            $editor->addChoice('GRANDE', 'Grande');
            $editColumn = new CustomEditColumn('Porte', 'porte', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for data_nascimento field
            //
            $editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data do Nascimento', 'data_nascimento', $editor, $this->dataset);
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
            $editColumn = new DynamicLookupEditColumn('Campanha', 'id_campanha', 'id_campanha_nome_campanha', 'edit_animal_id_campanha_search', $editor, $this->dataset, $lookupDataset, 'id_campanha', 'nome_campanha', '');
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
            $editColumn = new DynamicLookupEditColumn('Vacina', 'id_vacina', 'id_vacina_nome_vacina', 'edit_animal_id_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddEditColumn($editColumn);
            
            //
            // Edit column for data_aplicacao field
            //
            $editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data de Aplicação', 'data_aplicacao', $editor, $this->dataset);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $editColumn = new DynamicLookupEditColumn('CPF do Proprietario', 'cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'multi_edit_animal_cpf_proprietario_search', $editor, $this->dataset, $lookupDataset, 'cpf_proprietario', 'cpf_proprietario', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for nome_proprietario field
            //
            $editor = new TextEdit('nome_proprietario_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome do Proprietario', 'nome_proprietario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for nome_animal field
            //
            $editor = new TextEdit('nome_animal_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome do Animal', 'nome_animal', $editor, $this->dataset);
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
            $editColumn = new CustomEditColumn('Raça', 'raca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new RadioEdit('sexo_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice('FEMEA', 'FEMEA');
            $editor->addChoice('MACHO', 'MACHO');
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for porte field
            //
            $editor = new RadioEdit('porte_edit');
            $editor->SetDisplayMode(RadioEdit::StackedMode);
            $editor->addChoice('PEQUENO', 'Pequeno');
            $editor->addChoice('MEDIO', 'Médio');
            $editor->addChoice('GRANDE', 'Grande');
            $editColumn = new CustomEditColumn('Porte', 'porte', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for data_nascimento field
            //
            $editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data do Nascimento', 'data_nascimento', $editor, $this->dataset);
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
            $editColumn = new DynamicLookupEditColumn('Campanha', 'id_campanha', 'id_campanha_nome_campanha', 'multi_edit_animal_id_campanha_search', $editor, $this->dataset, $lookupDataset, 'id_campanha', 'nome_campanha', '');
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
            $editColumn = new DynamicLookupEditColumn('Vacina', 'id_vacina', 'id_vacina_nome_vacina', 'multi_edit_animal_id_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddMultiEditColumn($editColumn);
            
            //
            // Edit column for data_aplicacao field
            //
            $editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data de Aplicação', 'data_aplicacao', $editor, $this->dataset);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $editColumn = new DynamicLookupEditColumn('CPF do Proprietario', 'cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'insert_animal_cpf_proprietario_search', $editor, $this->dataset, $lookupDataset, 'cpf_proprietario', 'cpf_proprietario', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nome_proprietario field
            //
            $editor = new TextEdit('nome_proprietario_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome do Proprietario', 'nome_proprietario', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for nome_animal field
            //
            $editor = new TextEdit('nome_animal_edit');
            $editor->SetMaxLength(45);
            $editColumn = new CustomEditColumn('Nome do Animal', 'nome_animal', $editor, $this->dataset);
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
            $editColumn = new CustomEditColumn('Raça', 'raca', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for sexo field
            //
            $editor = new RadioEdit('sexo_edit');
            $editor->SetDisplayMode(RadioEdit::InlineMode);
            $editor->addChoice('FEMEA', 'FEMEA');
            $editor->addChoice('MACHO', 'MACHO');
            $editColumn = new CustomEditColumn('Sexo', 'sexo', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for porte field
            //
            $editor = new RadioEdit('porte_edit');
            $editor->SetDisplayMode(RadioEdit::StackedMode);
            $editor->addChoice('PEQUENO', 'Pequeno');
            $editor->addChoice('MEDIO', 'Médio');
            $editor->addChoice('GRANDE', 'Grande');
            $editColumn = new CustomEditColumn('Porte', 'porte', $editor, $this->dataset);
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for data_nascimento field
            //
            $editor = new DateTimeEdit('data_nascimento_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data do Nascimento', 'data_nascimento', $editor, $this->dataset);
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
            $editColumn = new DynamicLookupEditColumn('Campanha', 'id_campanha', 'id_campanha_nome_campanha', 'insert_animal_id_campanha_search', $editor, $this->dataset, $lookupDataset, 'id_campanha', 'nome_campanha', '');
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
            $editColumn = new DynamicLookupEditColumn('Vacina', 'id_vacina', 'id_vacina_nome_vacina', 'insert_animal_id_vacina_search', $editor, $this->dataset, $lookupDataset, 'id_vacina', 'nome_vacina', '');
            $validator = new RequiredValidator(StringUtils::Format($this->GetLocalizerCaptions()->GetMessageString('RequiredValidationMessage'), $editColumn->GetCaption()));
            $editor->GetValidatorCollection()->AddValidator($validator);
            $this->ApplyCommonColumnEditProperties($editColumn);
            $grid->AddInsertColumn($editColumn);
            
            //
            // Edit column for data_aplicacao field
            //
            $editor = new DateTimeEdit('data_aplicacao_edit', false, 'd-m-Y');
            $editColumn = new CustomEditColumn('Data de Aplicação', 'data_aplicacao', $editor, $this->dataset);
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
            // View column for cpf_proprietario field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'CPF do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome do Animal', $this->dataset);
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
            $column = new TextViewColumn('raca', 'raca', 'Raça', $this->dataset);
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
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data do Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddPrintColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data de Aplicação', $this->dataset);
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
            // View column for cpf_proprietario field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'CPF do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome do Animal', $this->dataset);
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
            $column = new TextViewColumn('raca', 'raca', 'Raça', $this->dataset);
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
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data do Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddExportColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data de Aplicação', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddExportColumn($column);
        }
    
        private function AddCompareColumns(Grid $grid)
        {
            //
            // View column for cpf_proprietario field
            //
            $column = new TextViewColumn('cpf_proprietario', 'cpf_proprietario_cpf_proprietario', 'CPF do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_proprietario field
            //
            $column = new TextViewColumn('nome_proprietario', 'nome_proprietario', 'Nome do Proprietario', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_animal field
            //
            $column = new TextViewColumn('nome_animal', 'nome_animal', 'Nome do Animal', $this->dataset);
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
            $column = new TextViewColumn('raca', 'raca', 'Raça', $this->dataset);
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
            $column = new DateTimeViewColumn('data_nascimento', 'data_nascimento', 'Data do Nascimento', $this->dataset);
            $column->SetOrderable(true);
            $column->SetDateTimeFormat('Y-m-d');
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_campanha field
            //
            $column = new TextViewColumn('id_campanha', 'id_campanha_nome_campanha', 'Campanha', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for nome_vacina field
            //
            $column = new TextViewColumn('id_vacina', 'id_vacina_nome_vacina', 'Vacina', $this->dataset);
            $column->SetOrderable(true);
            $grid->AddCompareColumn($column);
            
            //
            // View column for data_aplicacao field
            //
            $column = new DateTimeViewColumn('data_aplicacao', 'data_aplicacao', 'Data de Aplicação', $this->dataset);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_animal_cpf_proprietario_search', 'cpf_proprietario', 'cpf_proprietario', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'insert_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_animal_cpf_proprietario_search', 'cpf_proprietario', 'cpf_proprietario', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'filter_builder_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_animal_cpf_proprietario_search', 'cpf_proprietario', 'cpf_proprietario', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'edit_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
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
            $lookupDataset->setOrderByField('cpf_proprietario', 'ASC');
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_animal_cpf_proprietario_search', 'cpf_proprietario', 'cpf_proprietario', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_animal_id_campanha_search', 'id_campanha', 'nome_campanha', null, 20);
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
            $handler = new DynamicSearchHandler($lookupDataset, $this, 'multi_edit_animal_id_vacina_search', 'id_vacina', 'nome_vacina', null, 20);
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
        $Page = new animalPage("animal", "animal.php", GetCurrentUserPermissionsForPage("animal"), 'UTF-8');
        $Page->SetRecordPermission(GetCurrentUserRecordPermissionsForDataSource("animal"));
        GetApplication()->SetMainPage($Page);
        GetApplication()->Run();
    }
    catch(Exception $e)
    {
        ShowErrorPage($e);
    }
	
