<?php include_once APP_ROOT . '/views/includes/header.php'; ?>

<div class="px-4 mt-8">
  <a href="javascript:history.back()" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm mb-4">
    <i class="fas fa-arrow-left mr-2"></i>
    Terug
  </a>

  <div class="bg-white rounded-lg shadow-md max-w-2xl mx-auto">
    <div class="bg-primary rounded-t-lg text-white px-4 py-3">
      <div class="flex items-center">
        <i class="fa-solid fa-calendar-days mr-2"></i>
        <span>Nieuw boekjaar toevoegen</span>
      </div>
    </div>

    <?php flash('bookyear_message'); ?>

    <form action="<?php echo URL_ROOT; ?>/bookyear/addBookyear" method="post" class="space-y-6 p-6">
      <div>
        <label for="year" class="block text-sm font-medium text-gray-700">Jaar <span class="text-red-500">*</span></label>
        <input type="text" name="year" id="year"
          class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                      focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                      <?php echo !empty($data['year_err']) ? 'border-red-500' : ''; ?>"
          value="<?php echo $data['year'] ?? ''; ?>" required>
        <?php if (!empty($data['year_err'])): ?>
          <p class="mt-1 text-sm text-red-500"><?php echo $data['year_err']; ?></p>
        <?php endif; ?>
      </div>

      <div>
        <label for="description" class="block text-sm font-medium text-gray-700">Omschrijving</label>
        <input type="text" name="description" id="description"
          class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                      focus:ring-2 focus:ring-primary focus:border-primary transition-colors
                      <?php echo !empty($data['description_err']) ? 'border-red-500' : ''; ?>"
          value="<?php echo $data['description'] ?? ''; ?>">
        <?php if (!empty($data['description_err'])): ?>
          <p class="mt-1 text-sm text-red-500"><?php echo $data['description_err']; ?></p>
        <?php endif; ?>
      </div>

      <div class="flex justify-end space-x-4">
        <a href="<?php echo URL_ROOT; ?>/contributions/index"
          class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
          Annuleren
        </a>
        <button type="submit"
          class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
          <i class="fas fa-plus mr-2"></i> Boekjaar toevoegen
        </button>
      </div>

    </form>
  </div>
</div>

<?php include_once APP_ROOT . '/views/includes/footer.php'; ?>