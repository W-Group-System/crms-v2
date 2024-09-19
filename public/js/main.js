function toggleSetup(event) {
  event.preventDefault(); 
  $('#tables2').collapse('toggle'); 
}
function toggleTables(event) {
  event.preventDefault();
  $('#tables').collapse('toggle'); 
}
function toggleSetupService(event) {
  event.preventDefault(); 
  $('#table_service2').collapse('toggle'); 
}
function toggleTablesService(event) {
  event.preventDefault();
  $('#table_service').collapse('toggle'); 
}
function toggleSetupProduct(event) {
  event.preventDefault(); 
  $('#table_product2').collapse('toggle'); 
}
function toggleTablesProduct(event) {
  event.preventDefault();
  $('#table_product').collapse('toggle'); 
}
function toggleSetupPricing(event) {
  event.preventDefault(); 
  $('#table_pricing2').collapse('toggle'); 
}
// Accounting   
function toggleModule(event) {
  event.preventDefault();
  $('#module').collapse('toggle'); 
}
function toggleSetupAccounting(event) {
  event.preventDefault(); 
  $('#nav_accounting').collapse('toggle'); 
}
function togglePaymentCurrency(event) {
  event.preventDefault(); 
  $('#nav_payment_currency').collapse('toggle'); 
}
function toggleBusiness(event) {
  event.preventDefault(); 
  $('#nav_business').collapse('toggle'); 
}
function toggleLocation(event) {
  event.preventDefault(); 
  $('#nav_location').collapse('toggle'); 
}
// Product Management (Products)
function toggleProducts(event) {
  event.preventDefault(); 
  $('#nav_products').collapse('toggle'); 
}
// Supplier Transaction
function toggleSupplier(event) {
  event.preventDefault(); 
  $('#table_supplier').collapse('toggle'); 
}

setInterval(myTimer, 1000);

function myTimer() {
  const d = new Date();
  document.getElementById("demo").innerHTML = d.toLocaleTimeString();
}