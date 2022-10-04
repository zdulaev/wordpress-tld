<div class="withdrawal">

    <?php foreach ($post_meta_keys as $post_meta_key => $name): ?>

        <div class="withdrawal__type">
            <div class="withdrawal__name">
                <p><strong><?= $name ?></strong></p>
            </div>
            <div class="withdrawal__values">

                <?php foreach ($fields as $field): ?>

                    <?php if ($field === 'input.name'): ?>
                        <input 
                            type="text" 
                            name="<?= "{$pre_meta_key}[$post_meta_key][$field]" ?>" 
                            value="<?php 
                                if (isset($post_metas["$pre_meta_key.$post_meta_key.$field"])) {
                                    echo htmlspecialchars($post_metas["$pre_meta_key.$post_meta_key.$field"]['meta_value']);
                                }
                            ?>" 
                        >
                    <?php elseif ($field === 'select.post_id') : ?>
                        <select class="" name="<?= "{$pre_meta_key}[$post_meta_key][$field]" ?>">
                            <option value="0">&mdash; Выбрать &mdash;</option>

                            <?php foreach ($currencies as $currency): ?>
                                <option <?php 
                                    if (isset($post_metas["$pre_meta_key.$post_meta_key.$field"]) && $post_metas["$pre_meta_key.$post_meta_key.$field"]['meta_value'] == $currency->ID) {
                                        echo "selected";
                                    }
                                ?> value="<?= $currency->ID ?>"><?= $currency->post_title ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                <?php endforeach; ?>

            </div>
        </div>

    <?php endforeach; ?>

</div>
