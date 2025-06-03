<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<div class="px-4 py-6">

  <a href="<?php echo URL_ROOT; ?>/family/index" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm mb-4">
    <i class="fas fa-arrow-left mr-2"></i>
    Terug
  </a>

  <h1 class="text-3xl font-bold mb-6">Familie details</h1>

  <div class="bg-white rounded-lg shadow-md mb-6">
    <div class="bg-primary text-white px-6 py-4 rounded-t-lg">
      <h2 class="text-xl font-semibold">Familie <?php echo $data['family']->last_name; ?></h2>
    </div>
    <div class="p-6">
      <p class="mb-2"><span class="font-semibold">Adres:</span> <?php echo $data['family']->street; ?> <?php echo $data['family']->house_number; ?>, <?php echo $data['family']->postal_code; ?>, <?php echo $data['family']->city; ?></p>
      <p class="mb-2"><span class="font-semibold">Openstaande contributie:</span>
        <span class="<?php echo ($data['family']->total_contribution > 0) ? 'text-red-600' : 'text-green-600'; ?>">
          €<?php echo number_format($data['family']->total_contribution, 2, ',', '.'); ?>
        </span>
      </p>
      <p class="mb-2"><span class="font-semibold">Boekjaar:</span> <?php echo $_SESSION['bookyear']; ?></p>
    </div>
  </div>

  <div class="flex justify-between items-center mb-4">
    <h3 class="text-2xl font-semibold">Familieleden</h3>
    <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'secretaris'): ?>
      <a href="<?php echo URL_ROOT; ?>/familyMember/addFamilyMember/<?php echo $data['family']->id; ?>" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md flex items-center">
        <i class="fas fa-plus mr-2"></i>
        Lid toevoegen
      </a>
    <?php endif; ?>
  </div>

  <?php flash('family_member_message'); ?>

  <div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full">
        <thead class="bg-primary text-white">
          <tr>
            <th class="w-1/6 px-6 py-3 text-left">Naam</th>
            <th class="w-1/6 px-6 py-3 text-left">Geboortedatum</th>
            <th class="w-1/6 px-6 py-3 text-left">Leeftijd</th>
            <th class="w-1/6 px-6 py-3 text-left">Type lid</th>
            <th class="w-1/6 px-6 py-3 text-left">Openstaande contributie</th>
            <th class="w-1/6 px-6 py-3 text-left">Acties</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <?php foreach ($data['family_members'] as $member) : ?>
            <tr class="hover:bg-gray-50">
              <td class="w-1/6 px-6 py-4 whitespace-nowrap"><?php echo $member->first_name; ?></td>
              <td class="w-1/6 px-6 py-4 whitespace-nowrap"><?php echo date('d-m-Y', strtotime($member->date_of_birth)); ?></td>
              <td class="w-1/6 px-6 py-4 whitespace-nowrap"><?php echo calculateAge($member->date_of_birth); ?></td>
              <td class="w-1/6 px-6 py-4 whitespace-nowrap"><?php echo $member->member_type; ?></td>
              <td class="w-1/6 px-6 py-4 whitespace-nowrap"><span class="<?php echo ($member->contribution > 0) ? 'text-red-600' : 'text-green-600'; ?>">€<?php echo number_format($member->contribution, 2, ',', '.'); ?></span> </td>
              <td class="w-1/6 px-6 py-4 whitespace-nowrap space-x-2">
                <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'secretaris'): ?>
                  <a href="<?php echo URL_ROOT; ?>/familyMember/editFamilyMember/<?php echo $member->id; ?>" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm">
                    <i class="fas fa-edit mr-2"></i>
                    Bewerken
                  </a>
                <?php endif; ?>
                <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'penningmeester'): ?>
                  <a href="<?php echo URL_ROOT; ?>/contributions/addContribution/<?php echo $member->id; ?>" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm">
                    <i class="fas fa-credit-card mr-2"></i>
                    Betalen
                  </a>
                <?php endif; ?>
                <?php if ($_SESSION['userRole'] == 'admin' || $_SESSION['userRole'] == 'secretaris'): ?>
                  <a href="<?php echo URL_ROOT; ?>/familyMember/deleteFamilyMember/<?php echo $member->id; ?>/<?php echo $member->family_id; ?>" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm" onclick="return confirm('Weet u zeker dat u dit familielid wilt verwijderen? Hiermee verwijdert u ook alle contributies');">
                    <i class="fas fa-trash mr-2"></i>
                    Verwijderen
                  </a>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php
function calculateAge($birthDate)
{
  $today = new DateTime();
  $birth = new DateTime($birthDate);
  $age = $birth->diff($today)->y;
  return $age;
}

include_once APP_ROOT . '/views/includes/footer.php';
?>