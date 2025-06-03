<?php include_once APP_ROOT . '/views/includes/header.php'; ?>
<div class="px-4 mt-8">
  <a href="javascript:history.back()" class="inline-flex items-center bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm mb-4">
    <i class="fas fa-arrow-left mr-2"></i>
    Terug
  </a>
  <div class="bg-white rounded-lg shadow-md max-w-2xl mx-auto">
    <div class="bg-primary rounded-t-lg text-white px-4 py-3">
      <div class="flex items-center">
        <i class="fas fa-calendar-alt mr-2 text-white"></i>
        <span>Boekjaar bewerken</span>
      </div>
    </div>

    <?php flash('bookyear_message'); ?>

    <form action="<?php echo URL_ROOT; ?>/bookyear/editBookyear/<?php echo $data['bookyear']->year; ?>" method="post" class="space-y-6 p-6">
      <div class="mb-4">
        <label for="year" class="block text-sm font-medium text-gray-700">Jaar</label>
        <input type="text" name="year" id="year" value="<?php echo $data['bookyear']->year; ?>" class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                      focus:ring-2 focus:ring-primary focus:border-primary transition-colors">
        <span class="text-red-500 text-sm"><?php echo $data['year_err'] ?? ''; ?></span>
      </div>
      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Omschrijving</label>
        <input type="text" name="description" id="description" value="<?php echo $data['bookyear']->description ?? ''; ?>" class="mt-1 block w-full rounded-lg border-gray-500 bg-gray-50 shadow-md min-h-12 py-3 px-4 text-base
                      focus:ring-2 focus:ring-primary focus:border-primary transition-colors0">
        <span class="text-red-500 text-sm"><?php echo $data['description_err'] ?? ''; ?></span>
      </div>
      <div class="flex justify-end space-x-4">
        <button type="submit"
          class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-md hover:bg-primary-dark transition-colors">
          <i class="fas fa-edit mr-2"></i> Boekjaar bewerken
        </button>
        <a href="<?php echo URL_ROOT; ?>/contributions/index"
          class="inline-flex items-center px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 transition-colors">
          Annuleren
        </a>

      </div>
    </form>
  </div>
  <?php include_once APP_ROOT . '/views/includes/footer.php'; ?>