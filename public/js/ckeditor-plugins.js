// MathType plugin loader
function MathTypePlugin(editor) {
    return;
}

// Mermaid custom plugin
function MermaidPlugin(editor) {
    editor.ui.componentFactory.add("mermaidDiagram", locale => {
        const view = new editor.ui.button.ButtonView(locale);

        view.set({
            label: "Mermaid Diagram",
            withText: true,
            tooltip: true
        });

        view.on("execute", () => {
            const mermaidTemplate =
`\`\`\`mermaid
graph TD
    A[Start] --> B{Decision}
    B -->|Yes| C[Do Something]
    B -->|No| D[Do Something Else]
\`\`\`
`;
            editor.model.change(writer => {
                editor.model.insertContent(writer.createText(mermaidTemplate));
            });
        });

        return view;
    });
}
