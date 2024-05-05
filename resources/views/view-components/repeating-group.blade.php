<div class="repeating-group-wrapper">
    <div id="repeater-wrapper-{{ $getId() }}" class="repeater-wrapper">
        {!! $getChildComponents() !!}
    </div>

    <div data-repeat-section="repeater-wrapper-{{ $getId() }}"
         class="card d-flex flex-row py-4 px-2 justify-content-center align-items-center bg-success text-white"
         style="cursor:pointer;">
        <i class="fa fa-plus mr-2"></i><span>{{ __(':add_more_button_name', ['add_more_button_name' => $getAddMoreButtonName()]) }}</span>
    </div>
</div>

@pushonce('scripts')
    <script>
        $(document).ready(function () {
            let selectorQuery = '.repeating-group-wrapper .repeater-wrapper div.repeater';
            let numberOfRepetitions = $(selectorQuery).length;

            let dynamicComponents = `{{ $getRepeatingSchema() }}`;
            dynamicComponents = unescapeHTML(dynamicComponents);


            $('[data-repeat-section]').on('click', function () {
                let targetWrapper = $(this).data('repeat-section');
                let repeaterHtml = getRepeaterHtml(numberOfRepetitions);

                // let firstNationalitySelect = '#repeater-wrapper-1 #nationality_0';
                // $(firstNationalitySelect).select2('destroy');

                document.getElementById(targetWrapper).append(stringToHTML(repeaterHtml));

                let newRepeater = "#repeater-" + numberOfRepetitions;

                bindSelectCustom($(newRepeater));


                // $(firstNationalitySelect).select2();
                numberOfRepetitions++;

            });

            let stringToHTML = function (str) {
                let parser = new DOMParser();
                let doc = parser.parseFromString(str, 'text/html');
                let body = doc.body;
                let fragment = document.createDocumentFragment();
                // Append child nodes of the body to the fragment
                while (body.firstChild) {
                    fragment.appendChild(body.firstChild);
                }

                return fragment; // Return the fragment without the body tag
            };

            function getRepeaterHtml(repetitionId) {
                let modifiedString = replaceIdInHTMLString(dynamicComponents, repetitionId);
                let repeater = `<div id="repeater-` + repetitionId + `" class="repeater">`;
                repeater += modifiedString;
                repeater += `</div>`;

                return repeater;
            }

            function unescapeHTML(html) {
                let el = document.createElement('div');
                el.innerHTML = html;
                return el.innerText || el.textContent;
            }

            function replaceIdInHTMLString(htmlString, suffix) {
                let id_suffix = 'id="$1_' + suffix + '"';
                htmlString = htmlString.replace(/id="([A-Za-z0-9_-]*)_0"/g, id_suffix);

                let enable_suffix = 'data-enable-elem="#$1_' + suffix + '"';
                htmlString = htmlString.replace(/data-enable-elem="#([A-Za-z0-9_-]*)_0"/g, enable_suffix);

                let field_group = '{{ $getId() }}';
                let regex = new RegExp('name="' + field_group + '\\[0\\]', 'g');
                let field_names = 'name="' + field_group + '[' + suffix + ']';
                htmlString = htmlString.replace(regex, field_names);
                return htmlString;
            }


        });
    </script>
@endpushonce('scripts')
