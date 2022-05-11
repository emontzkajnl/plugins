<div>
    <div class="stmt-to-typography">
        Font Family
        <select v-model="typography['font-family']" placeholder="adas">
            <?php foreach (stmt_get_google_fonts() as $k => $val) : ?>
                <option value="<?php echo $k?>"><?php echo $val;?></option>
            <?php endforeach; ?>
        </select>

        Color
        <div class="stmt_colorpicker_wrapper">
            <span v-bind:style="{'background-color': color}"></span>
            <input type="text" v-model="color" />
            <stmt-color v-on:get-color="color = $event"></stmt-color>
        </div>

        Font Size
        <input type="text" v-model="typography['font-size']" />

        Font Weight
        <input type="text" v-model="typography['font-weight']" />

        Line Height
        <input type="text" v-model="typography['line-height']" />

        Letter Spacing
        <input type="text" v-model="typography['letter-spacing']" />

        Add classes and tags separating with comma (ex: .class, h1, h2 …)
        <input type="text" v-model="selectors" />
    </div>
</div>