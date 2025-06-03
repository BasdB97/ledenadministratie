<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<div class="px-6 py-8">

  <a href="javascript:history.back()" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm mb-4">
    <i class="fas fa-arrow-left mr-2"></i>
    Terug
  </a>

  <div class="bg-white rounded-lg shadow-md p-6 max-w-sm mx-auto">
    <form action="<?php echo URL_ROOT; ?>/contributions/addContribution" method="POST" class="space-y-6" id="contributionForm">
      <div class="mb-6">
        <h3 class="text-xl font-semibold">
          Familie <?php echo htmlspecialchars($data['familyMembers'][0]->last_name ?? 'Familie'); ?>
        </h3>
      </div>

      <div>
        <label for="member_id" class="block text-sm font-medium text-gray-700">Kies een Lid</label>
        <select name="member_id" id="member_id" class="mt-1 block w-full p-2 border rounded-lg" required onchange="updateFormAction()">
          <option value="">Selecteer een lid</option>
          <?php
          if (isset($data['familyMembers']) && is_array($data['familyMembers'])) {
            foreach ($data['familyMembers'] as $member) {
              $selected = isset($_POST['member_id']) && $_POST['member_id'] == $member->id ? 'selected' : '';
              echo "<option value=\"{$member->id}\" $selected>{$member->first_name} (€{$member->contribution})</option>";
            }
          }
          ?>
        </select>
      </div>

      <!-- Verborgen veld voor bookyearId -->
      <input type="hidden" name="bookyear_id" value="<?php echo htmlspecialchars($data['bookyearId'] ?? ''); ?>">

      <div class="flex justify-end">
        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
          <i class="fas fa-arrow-right mr-2"></i> Ga naar Betaling
        </button>
      </div>
    </form>
  </div>
</div>

<script>
  function updateFormAction() {
    const memberId = document.getElementById('member_id').value;
    if (memberId) {
      document.getElementById('contributionForm').action = '<?php echo URL_ROOT; ?>/contributions/addContribution/' + memberId;
    } else {
      document.getElementById('contributionForm').action = '<?php echo URL_ROOT; ?>/contributions/addContribution';
    }
  }
</script>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>