<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<div class="px-6 py-8">
  <div class="flex justify-between items-center mb-4">
    <h1 class="text-3xl font-bold mb-2">
      Dashboard - <?php echo ucfirst($_SESSION['userRole']); ?>
    </h1>
  </div>

  <h2 class="text-xl font-bold text-gray-700">
    Welkom op het dashboard van de ledenadministratie. Hier vind u het overzicht van de families van dit jaar. U kunt per familie de leden bekijken, aanpassen of verwijderen. Ook kunt u hier contributie betalingen toevoegen of de gehele familie verwijderen.
  </h2>
  <span class="text-red-500 ">Let op! Bij het verwijderen van een familie, worden leden en hun openstaande contributie ook verwijdert. Let hier dus mee op. </span>
  <div class="flex items-center my-6 text-gray-700">
    <div class="mr-6">
      <span class="font-semibold">Boekjaar:</span>
      <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-md ml-1">
        <?php echo $_SESSION['bookyear']; ?>
      </span>
    </div>
  </div>
</div>

<?php flash('family_message'); ?>
<?php flash('contribution_message'); ?>

<h2 class="text-xl font-bold text-gray-800 mb-4">
  Families overzicht
</h2>

<?php if (empty($data['families'])): ?>
  <div class="text-center text-red-500 py-8">Geen families gevonden.</div>
<?php else: ?>
  <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($data['families'] as $family): ?>
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-200">
        <div class="bg-primary text-white px-6 py-4">
          <h3 class="text-xl font-semibold">Familie <?php echo htmlspecialchars($family->last_name); ?></h3>
        </div>
        <div class="p-6 space-y-4">
          <div class="flex items-start space-x-2">
            <i class="fa-solid fa-location-dot text-gray-500 mt-1"></i>
            <p class="text-gray-700">
              <?php echo htmlspecialchars($family->street . ' ' . $family->house_number . ', ' . $family->postal_code . ' ' . $family->city . ', ' . $family->country); ?>
            </p>
          </div>

          <div class="flex items-center space-x-2">
            <i class="fa-solid fa-users text-gray-500"></i>
            <span class="text-blue-800 font-medium"><?php echo $family->member_count; ?> <?php echo $family->member_count == 1 ? 'lid' : 'leden'; ?></span>
          </div>

          <div class="flex items-center space-x-2">
            <i class="fa-solid fa-euro-sign text-gray-500"></i>
            <span class="<?php echo $family->totalContribution > 0 ? 'text-red-600' : 'text-green-600'; ?> font-medium">
              € <?php echo number_format($family->totalContribution, 2, ',', '.'); ?>
            </span>
          </div>

          <div class="pt-4 flex flex-wrap gap-2">

            <a href="<?php echo URL_ROOT; ?>/family/listFamilyDetails/<?php echo $family->id; ?>"
              class="bg-primary hover:bg-primary-dark text-white py-2 px-4 rounded-md transition-colors duration-200 flex items-center text-sm">
              <i class="fa-solid fa-eye mr-2"></i> Bekijken
            </a>


            <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'penningmeester'): ?>
              <a href="<?php echo URL_ROOT; ?>/contributions/selectFamilyMember/<?php echo $family->id; ?>"
                class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md transition-colors duration-200 flex items-center text-sm">
                <i class="fa-solid fa-credit-card mr-2"></i> Betalen
              </a>
            <?php endif; ?>

            <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'secretaris'): ?>
              <a href="<?php echo URL_ROOT; ?>/family/deleteFamily/<?php echo $family->id; ?>"
                class="bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md transition-colors duration-200 flex items-center text-sm"
                onclick="return confirm('Weet je zeker dat je deze familie wilt verwijderen? Alle leden worden ook verwijderd! Dit is een onomkeerbare actie.');"
                title="Verwijderen">
                <i class="fa-solid fa-trash mr-2"></i> Verwijderen
              </a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
<?php endif; ?>
</div>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>