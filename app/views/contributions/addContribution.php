<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<div class="px-6 py-8">

  <div class="bg-white rounded-lg shadow-md max-w-2xl mx-auto">
    <div class="bg-blue-600 rounded-t-lg text-white px-4 py-3">
      <div class="flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
          <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6z" />
        </svg>
        <span>Betaling toevoegen</span>
      </div>
    </div>

    <!-- Formulier voor één lid -->
    <form action="<?php echo URL_ROOT; ?>/contributions/processPayment/<?php echo $data['familyMember']->id; ?>" method="POST" class="space-y-6 p-6">

      <?php flash('contribution_message'); ?>

      <div class="flex space-x-4 mb-4">
        <label class="w-1/3 p-2 font-medium">Naam:</label>
        <span class="w-2/3 p-2 rounded-lg"><?php echo $data['familyMember']->first_name; ?></span>
      </div>
      <div class="flex space-x-4 mb-4">
        <label class="w-1/3 p-2 font-medium">Boekjaar:</label>
        <span class="w-2/3 p-2 rounded-lg"><?php echo $_SESSION['bookyear']; ?></span>
      </div>
      <div class="flex space-x-4 mb-4">
        <label class="w-1/3 p-2 font-medium">Openstaande contributie:</label>
        <span class="w-2/3 p-2 rounded-lg">€ <?php echo $data['familyMember']->amount; ?></span>
      </div>
      <div class="flex space-x-4 mb-4">
        <label class="w-1/3 p-2 font-medium">Bedrag:</label>
        <input type="number" name="amount" step="1" placeholder="Bedrag"
          class="w-2/3 p-2 border rounded-lg" required>
      </div>
      <?php if (!empty($data['contribution_err'])): ?>
        <p class="mt-1 text-sm text-red-500"><?php echo $data['contribution_err']; ?></p>
      <?php endif; ?>
      <p class="mb-4 text-sm text-gray-600">Let op: Bedragen met een '-' teken <strong>verhogen</strong> het contributiebedrag.</p>
      <div class="flex justify-end space-x-2">

        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
          <i class="fas fa-plus mr-2"></i> Betaling Toevoegen
        </button>
        <a href="<?php echo URL_ROOT; ?>/contributions/index" class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
          <i class="fas fa-arrow-left mr-2"></i> Naar contributie overzicht
        </a>
      </div>
    </form>

    <!-- Succes- of foutmelding -->
    <?php if (isset($data['message'])): ?>
      <div class="mt-4 p-4 <?php echo strpos($data['message'], 'succes') !== false ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'; ?> rounded-lg">
        <?php echo htmlspecialchars($data['message']); ?>
      </div>
    <?php endif; ?>
  </div>
</div>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>