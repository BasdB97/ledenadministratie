<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<div class="px-4 mt-8">
  <h2 class="text-3xl font-bold mb-6">Boekjaar beheer</h2>

  <?php flash('bookyear_message'); ?>

  <!-- Book Years Section -->
  <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="flex justify-between items-center mb-4">
      <h3 class="text-xl font-semibold">Boekjaren</h3>
      <a href="<?php echo URL_ROOT; ?>/bookyear/addBookyear" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md">
        <i class="fas fa-plus"></i>
        Nieuw Boekjaar
      </a>
    </div>
    <h2 class="text-lg text-gray-700">Hieronder kunt u een boekjaar toevoegen of verwijderen. Ook kunt u de omschrijving van de boekjaren aanpassen.
      <h3 class="text-m mb-4 text-gray-700">Een actief boekjaar kan niet worden verwijderd. Selecteer eerst een ander boekjaar voordat u een boekjaar verwijdert.</h3>
      <div class="overflow-x-auto">
        <table class="min-w-full bg-white">
          <thead>
            <tr class="bg-gray-100">
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Boekjaar</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Omschrijving</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actief</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Openstaande Contributie</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acties</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($data['bookyears'] as $bookyear) : ?>
              <tr class="border-b">
                <td class="px-6 py-4"><?php echo $bookyear->year; ?></td>
                <td class="px-6 py-4"><?php echo isset($bookyear->description) ? $bookyear->description : '/'; ?></td>
                <td class="px-6 py-4"><?php echo $bookyear->year == $_SESSION['bookyear'] ? '<i class="fa-solid fa-circle-check fa-xl" style="color: #63E6BE;"></i>' : '<i class="fa-solid fa-circle-xmark fa-xl" style="color: #ff0000;"></i>'; ?></td>
                <td class="px-6 py-4">€<?php echo number_format($bookyear->total_contribution, 2); ?></td>
                <td class="px-6 py-4">
                  <a href="<?php echo URL_ROOT; ?>/bookyear/editBookyear/<?php echo $bookyear->year; ?>"
                    class="inline-flex items-center justify-center w-8 h-8 bg-primary text-white rounded hover:bg-primary-dark transition-colors"
                    title="Bewerken">
                    <i class="fas fa-edit"></i>
                  </a>
                  <a href="<?php echo URL_ROOT; ?>/bookyear/deleteBookyear/<?php echo $bookyear->id; ?>"
                    class="inline-flex items-center justify-center w-8 h-8 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                    onclick="return confirm('Weet je zeker dat je dit boekjaar wilt verwijderen? Alle contributies worden ook verwijderd! Dit is een onomkeerbare actie.');"
                    title="Verwijderen">
                    <i class="fas fa-trash"></i>
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
  </div>


  <?php flash('contribution_message'); ?>

  <!-- Contributie overzicht -->
  <h2 class="text-3xl font-bold mb-6">Contributie beheer</h2>
  <div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-xl font-semibold mb-4">Contributie overzicht boekjaar <?php echo $_SESSION['bookyear']; ?></h3>
    <h3 class="text-m mb-4 text-gray-700">Hier kunt u contributies betalen of verwijderen.</h3>
    <table class="min-w-full bg-white">
      <thead>
        <tr class="bg-gray-100">
          <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase ">Voornaam</th>
          <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase ">Achternaam</th>
          <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase ">Openstaande contributie</th>
          <th class="w-1/4 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase ">Acties</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($data['familyMembers'] as $member) : ?>
          <tr class="border-b">
            <td class="px-6 py-4"><?php echo $member->first_name; ?></td>
            <td class="px-6 py-4 hover:underline hover:text-blue-500"><a href="<?php echo URL_ROOT; ?>/family/listFamilyDetails/<?php echo $member->family_id; ?>"><?php echo $member->last_name; ?></a></td>
            <td class="px-6 py-4 <?php echo ($member->outstanding_contribution > 0) ? 'text-red-600' : 'text-green-600'; ?>">€<?php echo number_format($member->outstanding_contribution, 2); ?></td>
            <td class="px-6 py-4 space-x-2">
              <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'penningmeester'): ?>
                <a href="<?php echo URL_ROOT; ?>/contributions/addContribution/<?php echo $member->id; ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                  <i class="fas fa-credit-card mr-2"></i>
                  Betalen
                </a>
              <?php endif; ?>
              <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'secretaris'): ?>
                <a href="<?php echo URL_ROOT; ?>/contributions/deleteContribution/<?php echo $member->id; ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm" onclick="return confirm('Weet u zeker dat u dit familielid wilt verwijderen? Hiermee verwijdert u ook alle contributies');">
                  <i class="fas fa-trash mr-2"></i>
                  Verwijderen
                </a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
    <?php if (!empty($data['bookyear_err'])): ?>
      <p class="mt-1 text-sm text-red-500"><?php echo $data['bookyear_err']; ?></p>
    <?php endif; ?>
  </div>
</div>
</div>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>