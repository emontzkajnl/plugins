<?php

$dir = realpath( __DIR__ . '/..' );

return array (
  'ACA\\GravityForms\\Admin' => $dir . '/classes/Admin.php',
  'ACA\\GravityForms\\Capabilities' => $dir . '/classes/Capabilities.php',
  'ACA\\GravityForms\\Column\\Entry' => $dir . '/classes/Column/Entry.php',
  'ACA\\GravityForms\\Column\\EntryConfigurator' => $dir . '/classes/Column/EntryConfigurator.php',
  'ACA\\GravityForms\\Column\\EntryFactory' => $dir . '/classes/Column/EntryFactory.php',
  'ACA\\GravityForms\\Column\\Entry\\Address' => $dir . '/classes/Column/Entry/Address.php',
  'ACA\\GravityForms\\Column\\Entry\\Choices' => $dir . '/classes/Column/Entry/Choices.php',
  'ACA\\GravityForms\\Column\\Entry\\Custom\\User' => $dir . '/classes/Column/Entry/Custom/User.php',
  'ACA\\GravityForms\\Column\\Entry\\MultipleChoices' => $dir . '/classes/Column/Entry/MultipleChoices.php',
  'ACA\\GravityForms\\Column\\Entry\\Name' => $dir . '/classes/Column/Entry/Name.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\DateCreated' => $dir . '/classes/Column/Entry/Original/DateCreated.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\DatePayment' => $dir . '/classes/Column/Entry/Original/DatePayment.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\EntryId' => $dir . '/classes/Column/Entry/Original/EntryId.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\PaymentAmount' => $dir . '/classes/Column/Entry/Original/PaymentAmount.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\SourceUrl' => $dir . '/classes/Column/Entry/Original/SourceUrl.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\Starred' => $dir . '/classes/Column/Entry/Original/Starred.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\TransactionId' => $dir . '/classes/Column/Entry/Original/TransactionId.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\User' => $dir . '/classes/Column/Entry/Original/User.php',
  'ACA\\GravityForms\\Column\\Entry\\Original\\UserIp' => $dir . '/classes/Column/Entry/Original/UserIp.php',
  'ACA\\GravityForms\\Column\\Entry\\Product' => $dir . '/classes/Column/Entry/Product.php',
  'ACA\\GravityForms\\Column\\Entry\\ProductSelect' => $dir . '/classes/Column/Entry/ProductSelect.php',
  'ACA\\GravityForms\\Dependencies' => $dir . '/classes/Dependencies.php',
  'ACA\\GravityForms\\Editing\\EditableRowsFactory' => $dir . '/classes/Editing/EditableRowsFactory.php',
  'ACA\\GravityForms\\Editing\\EditableRows\\Entry' => $dir . '/classes/Editing/EditableRows/Entry.php',
  'ACA\\GravityForms\\Editing\\EntryServiceFactory' => $dir . '/classes/Editing/EntryServiceFactory.php',
  'ACA\\GravityForms\\Editing\\Storage\\Entry' => $dir . '/classes/Editing/Storage/Entry.php',
  'ACA\\GravityForms\\Editing\\Storage\\Entry\\Checkbox' => $dir . '/classes/Editing/Storage/Entry/Checkbox.php',
  'ACA\\GravityForms\\Editing\\Storage\\Entry\\MultiSelect' => $dir . '/classes/Editing/Storage/Entry/MultiSelect.php',
  'ACA\\GravityForms\\Editing\\Strategy\\Entry' => $dir . '/classes/Editing/Strategy/Entry.php',
  'ACA\\GravityForms\\Editing\\TableRows\\Entry' => $dir . '/classes/Editing/TableRows/Entry.php',
  'ACA\\GravityForms\\Export\\Model\\EntryFactory' => $dir . '/classes/Export/Model/EntryFactory.php',
  'ACA\\GravityForms\\Export\\Model\\Entry\\Address' => $dir . '/classes/Export/Model/Entry/Address.php',
  'ACA\\GravityForms\\Export\\Model\\Entry\\Check' => $dir . '/classes/Export/Model/Entry/Check.php',
  'ACA\\GravityForms\\Export\\Model\\Entry\\ItemList' => $dir . '/classes/Export/Model/Entry/ItemList.php',
  'ACA\\GravityForms\\Export\\Strategy\\Entry' => $dir . '/classes/Export/Strategy/Entry.php',
  'ACA\\GravityForms\\Field' => $dir . '/classes/Field.php',
  'ACA\\GravityForms\\FieldFactory' => $dir . '/classes/FieldFactory.php',
  'ACA\\GravityForms\\FieldTypes' => $dir . '/classes/FieldTypes.php',
  'ACA\\GravityForms\\Field\\Container' => $dir . '/classes/Field/Container.php',
  'ACA\\GravityForms\\Field\\Field' => $dir . '/classes/Field/Field.php',
  'ACA\\GravityForms\\Field\\Multiple' => $dir . '/classes/Field/Multiple.php',
  'ACA\\GravityForms\\Field\\Number' => $dir . '/classes/Field/Number.php',
  'ACA\\GravityForms\\Field\\Options' => $dir . '/classes/Field/Options.php',
  'ACA\\GravityForms\\Field\\Type\\Address' => $dir . '/classes/Field/Type/Address.php',
  'ACA\\GravityForms\\Field\\Type\\Checkbox' => $dir . '/classes/Field/Type/Checkbox.php',
  'ACA\\GravityForms\\Field\\Type\\CheckboxGroup' => $dir . '/classes/Field/Type/CheckboxGroup.php',
  'ACA\\GravityForms\\Field\\Type\\Consent' => $dir . '/classes/Field/Type/Consent.php',
  'ACA\\GravityForms\\Field\\Type\\Date' => $dir . '/classes/Field/Type/Date.php',
  'ACA\\GravityForms\\Field\\Type\\Email' => $dir . '/classes/Field/Type/Email.php',
  'ACA\\GravityForms\\Field\\Type\\Input' => $dir . '/classes/Field/Type/Input.php',
  'ACA\\GravityForms\\Field\\Type\\ItemList' => $dir . '/classes/Field/Type/ItemList.php',
  'ACA\\GravityForms\\Field\\Type\\Name' => $dir . '/classes/Field/Type/Name.php',
  'ACA\\GravityForms\\Field\\Type\\Number' => $dir . '/classes/Field/Type/Number.php',
  'ACA\\GravityForms\\Field\\Type\\Product' => $dir . '/classes/Field/Type/Product.php',
  'ACA\\GravityForms\\Field\\Type\\ProductSelect' => $dir . '/classes/Field/Type/ProductSelect.php',
  'ACA\\GravityForms\\Field\\Type\\Radio' => $dir . '/classes/Field/Type/Radio.php',
  'ACA\\GravityForms\\Field\\Type\\Select' => $dir . '/classes/Field/Type/Select.php',
  'ACA\\GravityForms\\Field\\Type\\Textarea' => $dir . '/classes/Field/Type/Textarea.php',
  'ACA\\GravityForms\\Field\\Type\\Unsupported' => $dir . '/classes/Field/Type/Unsupported.php',
  'ACA\\GravityForms\\GravityForms' => $dir . '/classes/GravityForms.php',
  'ACA\\GravityForms\\HideOnScreen\\EntryFilters' => $dir . '/classes/HideOnScreen/EntryFilters.php',
  'ACA\\GravityForms\\HideOnScreen\\WordPressNotifications' => $dir . '/classes/HideOnScreen/WordPressNotifications.php',
  'ACA\\GravityForms\\ListScreen\\Entry' => $dir . '/classes/ListScreen/Entry.php',
  'ACA\\GravityForms\\ListTable' => $dir . '/classes/ListTable.php',
  'ACA\\GravityForms\\MetaTypes' => $dir . '/classes/MetaTypes.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry' => $dir . '/classes/Search/Comparison/Entry.php',
  'ACA\\GravityForms\\Search\\Comparison\\EntryFactory' => $dir . '/classes/Search/Comparison/EntryFactory.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Checkbox' => $dir . '/classes/Search/Comparison/Entry/Checkbox.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\CheckboxGroup' => $dir . '/classes/Search/Comparison/Entry/CheckboxGroup.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Choice' => $dir . '/classes/Search/Comparison/Entry/Choice.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Choices' => $dir . '/classes/Search/Comparison/Entry/Choices.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Consent' => $dir . '/classes/Search/Comparison/Entry/Consent.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Date' => $dir . '/classes/Search/Comparison/Entry/Date.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\DateColumn' => $dir . '/classes/Search/Comparison/Entry/DateColumn.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\EntryId' => $dir . '/classes/Search/Comparison/Entry/EntryId.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Number' => $dir . '/classes/Search/Comparison/Entry/Number.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\PaymentAmount' => $dir . '/classes/Search/Comparison/Entry/PaymentAmount.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Starred' => $dir . '/classes/Search/Comparison/Entry/Starred.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\Text' => $dir . '/classes/Search/Comparison/Entry/Text.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\TextColumn' => $dir . '/classes/Search/Comparison/Entry/TextColumn.php',
  'ACA\\GravityForms\\Search\\Comparison\\Entry\\User' => $dir . '/classes/Search/Comparison/Entry/User.php',
  'ACA\\GravityForms\\Search\\Query' => $dir . '/classes/Search/Query.php',
  'ACA\\GravityForms\\Search\\Query\\Bindings' => $dir . '/classes/Search/Query/Bindings.php',
  'ACA\\GravityForms\\Search\\TableScreen\\Entry' => $dir . '/classes/Search/TableScreen/Entry.php',
  'ACA\\GravityForms\\Settings\\ChoiceDisplay' => $dir . '/classes/Settings/ChoiceDisplay.php',
  'ACA\\GravityForms\\TableFactory' => $dir . '/classes/TableFactory.php',
  'ACA\\GravityForms\\TableScreen\\Entry' => $dir . '/classes/TableScreen/Entry.php',
  'ACA\\GravityForms\\Utils\\FormField' => $dir . '/classes/Utils/FormField.php',
  'ACA\\GravityForms\\Utils\\Hooks' => $dir . '/classes/Utils/Hooks.php',
  'ACA\\GravityForms\\Value' => $dir . '/classes/Value.php',
  'ACA\\GravityForms\\Value\\EntryValue' => $dir . '/classes/Value/EntryValue.php',
);