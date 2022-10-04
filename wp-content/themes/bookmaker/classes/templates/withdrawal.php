<div class="withdrawal">

    <div class="withdrawal__values">

        <?php foreach ($fields as $field): ?>

            <?php if ($field === 'input.name'): ?>
                <input 
                    type="text" 
                    name="<?= "{$pre_meta_key}[$field]" ?>" 
                    value="<?php 
                        if (isset($post_metas["$pre_meta_key.$field"])) {
                            echo htmlspecialchars($post_metas["$pre_meta_key.$field"]['meta_value']);
                        }
                    ?>" 
                >
            <?php elseif ($field === 'select.post_id') : ?>
                <select class="" name="<?= "{$pre_meta_key}[$field]" ?>">
                    <option value="0">&mdash; Выбрать &mdash;</option>

                    <?php foreach ($currencies as $currency): ?>
                        <option <?php 
                            if (isset($post_metas["$pre_meta_key.$field"]) && $post_metas["$pre_meta_key.$field"]['meta_value'] == $currency->ID) {
                                echo "selected";
                            }
                        ?> value="<?= $currency->ID ?>"><?= $currency->post_title ?></option>
                    <?php endforeach; ?>
                </select>
            <?php endif; ?>

        <?php endforeach; ?>

    </div>

</div>
